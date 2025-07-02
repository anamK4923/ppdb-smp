<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BerkasPendaftaran;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());
        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();
        $berkasPendaftaran = BerkasPendaftaran::where('id_user', $user->id)->first();

        // Timeline steps
        $timeline = $this->getTimelineSteps($pendaftaran, $berkasPendaftaran);

        return view('student.status.index', compact('pendaftaran', 'timeline', 'user', 'berkasPendaftaran'));
    }

    private function isPendaftaranLengkap($pendaftaran, $berkasPendaftaran)
    {
        if (!$pendaftaran) return false;
        if (!$berkasPendaftaran) return false;

        $requiredPendaftaranFields = [
            'nisn',
            'nik',
            'nama',
            'jenis_kelamin',
            'tempat_lahir',
            'tgl_lahir',
            'alamat',
            'sekolah_asal',
        ];

        $requiredBerkasFields = [
            'kk_file',
            'akta_file',
            'piagam_file',
            'foto',
        ];

        // Cek data biodata pendaftaran
        foreach ($requiredPendaftaranFields as $field) {
            if (empty($pendaftaran->$field)) {
                return false;
            }
        }

        // Cek data berkas
        foreach ($requiredBerkasFields as $field) {
            if (empty($berkasPendaftaran->$field)) {
                return false;
            }
        }

        return true;
    }

    private function getTimelineSteps($pendaftaran, $berkasPendaftaran)
    {
        $steps = [
            [
                'title' => 'Pendaftaran Akun',
                'description' => 'Akun berhasil dibuat',
                'icon' => 'fas fa-user-plus',
                'status' => 'completed',
                'date' => Auth::user()->created_at->format('d M Y')
            ],
            [
                'title' => 'Mengisi Data Pendaftaran',
                'description' => 'Lengkapi data pribadi dan upload dokumen',
                'icon' => 'fas fa-edit',
                'status' => $this->isPendaftaranLengkap($pendaftaran, $berkasPendaftaran) ? 'completed' : 'pending',
                'date' => $pendaftaran ? $pendaftaran->created_at->format('d M Y') : null
            ],
            [
                'title' => 'Verifikasi Data',
                'description' => 'Admin memverifikasi data dan dokumen',
                'icon' => 'fas fa-check-circle',
                'status' => $this->getVerificationStatus($pendaftaran),
                'date' => $pendaftaran && $pendaftaran->updated_at != $pendaftaran->created_at ?
                    $pendaftaran->updated_at->format('d M Y') : null
            ],
            [
                'title' => 'Seleksi',
                'description' => 'Proses seleksi calon siswa',
                'icon' => 'fas fa-clipboard-list',
                'status' => $this->getSelectionStatus($pendaftaran),
                'date' => $pendaftaran && $pendaftaran->updated_at != $pendaftaran->created_at ?
                    $pendaftaran->updated_at->format('d M Y') : null
            ],
            [
                'title' => 'Pengumuman Hasil',
                'description' => 'Pengumuman hasil seleksi',
                'icon' => 'fas fa-bullhorn',
                'status' => $this->getAnnouncementStatus($pendaftaran),
                'date' => $pendaftaran && $pendaftaran->updated_at != $pendaftaran->created_at ?
                    $pendaftaran->updated_at->format('d M Y') : null
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
            'Menunggu' => 'pending',
            default => 'disabled'
        };
    }

    private function getSelectionStatus($pendaftaran)
    {
        if (!$pendaftaran || $pendaftaran->status_verifikasi !== 'Terverifikasi') {
            return 'disabled';
        }

        return match ($pendaftaran->user->pengumuman->first()?->hasil_seleksi) {
            'Diterima' => 'completed',
            'Tidak Diterima' => 'rejected',
            'Dalam Proses' => 'pending',
            default => 'pending'
        };
    }

    private function getAnnouncementStatus($pendaftaran)
    {
        if (!$pendaftaran) return 'disabled';


        if (in_array($pendaftaran->user->pengumuman->first()?->hasil_seleksi, ['Diterima', 'Tidak Diterima'])) {
            return 'completed';
        }

        return 'disabled';
    }
}
