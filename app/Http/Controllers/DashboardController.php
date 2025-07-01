<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use App\Models\User;
use App\Models\BerkasPendaftaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }
    }

    public function admin(): View
    {
        // Statistik dasar
        $stats = [
            'total_pendaftar' => Pendaftaran::count(),
            'terverifikasi' => Pendaftaran::where('status_verifikasi', 'Terverifikasi')->count(),
            'belum_verifikasi' => Pendaftaran::where('status_verifikasi', 'Belum Diverifikasi')->count(),
            'ditolak' => Pendaftaran::where('status_verifikasi', 'Ditolak')->count(),
            'diterima' => Pengumuman::where('hasil_seleksi', 'Diterima')->count(),
            'tidak_diterima' => Pengumuman::where('hasil_seleksi', 'Tidak Diterima')->count(),
            'total_users' => User::count(),
        ];

        // Data untuk Chart Pendaftaran per Hari (7 hari terakhir)
        $chartData = [];
        $chartLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Pendaftaran::whereDate('created_at', $date)->count();
            $chartData[] = $count;
            $chartLabels[] = $date->format('d M');
        }

        // Data untuk Chart Verifikasi
        $verifikasiChart = [
            'labels' => ['Terverifikasi', 'Belum Diverifikasi', 'Ditolak'],
            'data' => [
                $stats['terverifikasi'],
                $stats['belum_verifikasi'],
                $stats['ditolak']
            ],
            'colors' => ['#28a745', '#ffc107', '#dc3545']
        ];

        // Pendaftar terbaru
        $pendaftarTerbaru = Pendaftaran::with('user')
            ->latest()
            ->limit(8)
            ->get()
            ->map(function ($pendaftar) {
                $pendaftar->status_verifikasi_badge = $this->getStatusBadge($pendaftar->status_verifikasi);
                return $pendaftar;
            });

        // Aktivitas terbaru
        $aktivitasTerbaru = $this->getAktivitasTerbaru();

        return view('dashboard.dashboard-admin', compact(
            'stats',
            'chartData',
            'chartLabels',
            'verifikasiChart',
            'pendaftarTerbaru',
            'aktivitasTerbaru'
        ));
    }

    public function student(): View
    {
        $user = Auth::user();
        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();
        $berkasPendaftaran = BerkasPendaftaran::where('id_user', $user->id)->first();
        $pengumuman = Pengumuman::where('user_id', $user->id)->first();

        // Hitung progress pendaftaran
        $progress = $this->calculateProgress($pendaftaran, $berkasPendaftaran);

        // Status timeline
        $timeline = $this->getTimelineStatus($pendaftaran, $pengumuman);

        // Statistik untuk student
        $stats = [
            'total_pendaftar' => Pendaftaran::count(),
            'sudah_diumumkan' => Pengumuman::count(),
            'diterima' => Pengumuman::where('hasil_seleksi', 'Diterima')->count(),
        ];

        // Notifikasi untuk student
        $notifikasi = $this->getNotifikasiStudent($pendaftaran, $pengumuman);

        // Quick actions
        $quickActions = $this->getQuickActions($pendaftaran, $berkasPendaftaran, $pengumuman);

        return view('dashboard.dashboard-student', compact(
            'user',
            'pendaftaran',
            'berkasPendaftaran',
            'pengumuman',
            'progress',
            'timeline',
            'stats',
            'notifikasi',
            'quickActions'
        ));
    }

    private function getBerkasLengkapCount()
    {
        return BerkasPendaftaran::whereNotNull('kk_file')
            ->whereNotNull('akta_file')
            ->whereNotNull('piagam_file')
            ->whereNotNull('foto')
            ->count();
    }

    private function getBerkasTidakLengkapCount()
    {
        $total = BerkasPendaftaran::count();
        return $total - $this->getBerkasLengkapCount();
    }

    private function getAktivitasTerbaru()
    {
        $aktivitas = collect();

        // Pendaftaran terbaru
        $pendaftaranBaru = Pendaftaran::with('user')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'pendaftaran',
                    'message' => $item->nama . ' mendaftar',
                    'time' => $item->created_at,
                    'icon' => 'fas fa-user-plus',
                    'color' => 'info'
                ];
            });

        // Pengumuman terbaru
        $pengumumanBaru = Pengumuman::with('user')
            ->latest()
            ->take(2)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'pengumuman',
                    'message' => 'Pengumuman untuk ' . $item->user->name . ' (' . $item->hasil_seleksi . ')',
                    'time' => $item->created_at,
                    'icon' => 'fas fa-bullhorn',
                    'color' => $item->hasil_seleksi == 'Diterima' ? 'success' : 'danger'
                ];
            });

        return $aktivitas->merge($pendaftaranBaru)
            ->merge($pengumumanBaru)
            ->sortByDesc('time')
            ->take(5);
    }

    private function calculateProgress($pendaftaran, $berkasPendaftaran)
    {
        if (!$pendaftaran) return 0;

        $fields = ['nisn', 'nik', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'alamat', 'sekolah_asal', 'agama', 'no_hp', 'nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu'];
        $files = ['kk_file', 'akta_file', 'foto', 'piagam_file'];

        $filledFields = 0;
        $totalFields = count($fields) + count($files);

        // Check text fields
        foreach ($fields as $field) {
            if (!empty($pendaftaran->$field)) {
                $filledFields++;
            }
        }

        // Check file fields
        if ($berkasPendaftaran) {
            foreach ($files as $file) {
                if (!empty($berkasPendaftaran->$file)) {
                    $filledFields++;
                }
            }
        }

        return round(($filledFields / $totalFields) * 100);
    }

    private function getTimelineStatus($pendaftaran, $pengumuman)
    {
        $steps = [
            [
                'title' => 'Pendaftaran Akun',
                'status' => 'completed',
                'date' => Auth::user()->created_at->format('d M Y')
            ],
            [
                'title' => 'Mengisi Data',
                'status' => $pendaftaran ? 'completed' : 'pending',
                'date' => $pendaftaran ? $pendaftaran->created_at->format('d M Y') : null
            ],
            [
                'title' => 'Verifikasi Admin',
                'status' => $this->getVerificationStatus($pendaftaran),
                'date' => $pendaftaran && $pendaftaran->status_verifikasi == 'Terverifikasi' ?
                    $pendaftaran->updated_at->format('d M Y') : null
            ],
            [
                'title' => 'Pengumuman',
                'status' => $pengumuman ? 'completed' : 'pending',
                'date' => $pengumuman ? Carbon::parse($pengumuman->tanggal)->format('d M Y') : null
            ]
        ];

        return $steps;
    }

    private function getVerificationStatus($pendaftaran)
    {
        if (!$pendaftaran) return 'disabled';

        return match ($pendaftaran->status_verifikasi) {
            'Terverifikasi' => 'completed',
            'Ditolak' => 'rejected',
            'Belum Diverifikasi' => 'pending',
            default => 'disabled'
        };
    }

    private function getNotifikasiStudent($pendaftaran, $pengumuman)
    {
        $notifikasi = [];

        if (!$pendaftaran) {
            $notifikasi[] = [
                'type' => 'warning',
                'message' => 'Silakan lengkapi data pendaftaran Anda.',
                'action' => route('student.pendaftaran.index'),
                'action_text' => 'Lengkapi Data'
            ];
        } elseif ($pendaftaran->status_verifikasi == 'Belum Diverifikasi') {
            $notifikasi[] = [
                'type' => 'info',
                'message' => 'Data Anda sedang dalam proses verifikasi admin.',
                'action' => null,
                'action_text' => null
            ];
        } elseif ($pendaftaran->status_verifikasi == 'Ditolak') {
            $notifikasi[] = [
                'type' => 'danger',
                'message' => 'Data Anda ditolak. Silakan perbaiki dan kirim ulang.',
                'action' => route('student.pendaftaran.index'),
                'action_text' => 'Perbaiki Data'
            ];
        }

        if ($pengumuman) {
            $type = $pengumuman->hasil_seleksi == 'Diterima' ? 'success' : 'danger';
            $notifikasi[] = [
                'type' => $type,
                'message' => 'Pengumuman hasil seleksi sudah tersedia: ' . $pengumuman->hasil_seleksi,
                'action' => route('student.pengumuman.hasil'),
                'action_text' => 'Lihat Hasil'
            ];
        }

        return $notifikasi;
    }

    private function getQuickActions($pendaftaran, $berkasPendaftaran, $pengumuman)
    {
        $actions = [];

        if (!$pendaftaran || $this->calculateProgress($pendaftaran, $berkasPendaftaran) < 100) {
            $actions[] = [
                'title' => 'Lengkapi Data Pendaftaran',
                'description' => 'Isi data pribadi dan upload dokumen',
                'url' => route('student.pendaftaran.index'),
                'icon' => 'fas fa-edit',
                'color' => 'primary'
            ];
        }

        $actions[] = [
            'title' => 'Lihat Status Pendaftaran',
            'description' => 'Cek progress dan timeline pendaftaran',
            'url' => route('student.status.index'),
            'icon' => 'fas fa-tasks',
            'color' => 'info'
        ];

        if ($pengumuman) {
            $actions[] = [
                'title' => 'Lihat Pengumuman',
                'description' => 'Hasil seleksi PPDB',
                'url' => route('student.pengumuman.hasil'),
                'icon' => 'fas fa-bullhorn',
                'color' => $pengumuman->hasil_seleksi == 'Diterima' ? 'success' : 'danger'
            ];
        }

        return $actions;
    }

    private function getStatusBadge($status)
    {
        switch ($status) {
            case 'Terverifikasi':
                return 'success';
            case 'Ditolak':
                return 'danger';
            case 'Belum Diverifikasi':
            default:
                return 'warning';
        }
    }
}
