<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        // Hanya ambil pendaftar yang sudah terverifikasi
        $query = Pendaftaran::with(['user', 'user.berkasPendaftaran'])
            ->where('status_verifikasi', 'Terverifikasi');

        // Filter berdasarkan nama
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan hasil seleksi
        if ($request->filled('hasil_seleksi')) {
            if ($request->hasil_seleksi == 'Belum Diumumkan') {
                $query->whereDoesntHave('user.pengumuman');
            } else {
                $query->whereHas('user.pengumuman', function ($q) use ($request) {
                    $q->where('hasil_seleksi', $request->hasil_seleksi);
                });
            }
        }

        // Filter berdasarkan sekolah asal
        if ($request->filled('sekolah_asal')) {
            $query->where('sekolah_asal', 'like', '%' . $request->sekolah_asal . '%');
        }

        $pendaftars = $query->latest()->paginate(15);

        // Ambil data pengumuman untuk setiap pendaftar
        $userIds = $pendaftars->pluck('user_id');
        $pengumumans = Pengumuman::whereIn('user_id', $userIds)->get()->keyBy('user_id');

        // Statistik
        $stats = [
            'total_terverifikasi' => Pendaftaran::where('status_verifikasi', 'Terverifikasi')->count(),
            'sudah_diumumkan' => Pengumuman::count(),
            'belum_diumumkan' => Pendaftaran::where('status_verifikasi', 'Terverifikasi')
                ->whereDoesntHave('user.pengumuman')->count(),
            'diterima' => Pengumuman::where('hasil_seleksi', 'Diterima')->count(),
            'tidak_diterima' => Pengumuman::where('hasil_seleksi', 'Tidak Diterima')->count(),
        ];

        return view('admin.pengumuman.index', compact('pendaftars', 'pengumumans', 'stats'));
    }

    public function updateBatch(Request $request)
    {
        $request->validate([
            'pendaftar_ids' => 'required|array|min:1',
            'pendaftar_ids.*' => 'exists:pendaftaran,id',
            'hasil_seleksi' => 'required|in:Diterima,Tidak Diterima',
            'tanggal_pengumuman' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $pendaftarIds = $request->pendaftar_ids;
            $hasilSeleksi = $request->hasil_seleksi;
            $tanggalPengumuman = $request->tanggal_pengumuman;

            // Ambil data pendaftar yang dipilih
            $pendaftars = Pendaftaran::whereIn('id', $pendaftarIds)
                ->where('status_verifikasi', 'Terverifikasi')
                ->get();

            $updated = 0;
            foreach ($pendaftars as $pendaftar) {
                // Update atau buat pengumuman
                Pengumuman::updateOrCreate(
                    ['user_id' => $pendaftar->user_id],
                    [
                        'hasil_seleksi' => $hasilSeleksi,
                        'tanggal' => $tanggalPengumuman,
                    ]
                );
                $updated++;
            }

            DB::commit();

            return redirect()->back()->with(
                'success',
                "Berhasil mengupdate hasil seleksi untuk {$updated} pendaftar menjadi '{$hasilSeleksi}'."
            );
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(
                'error',
                'Terjadi kesalahan saat mengupdate hasil seleksi: ' . $e->getMessage()
            );
        }
    }

    public function updateSingle(Request $request, $id)
    {
        $request->validate([
            'hasil_seleksi' => 'required|in:Diterima,Tidak Diterima',
            'tanggal_pengumuman' => 'required|date',
        ]);

        $pendaftar = Pendaftaran::where('id', $id)
            ->where('status_verifikasi', 'Terverifikasi')
            ->firstOrFail();

        // Update atau buat pengumuman
        Pengumuman::updateOrCreate(
            ['user_id' => $pendaftar->user_id],
            [
                'hasil_seleksi' => $request->hasil_seleksi,
                'tanggal' => $request->tanggal_pengumuman,
            ]
        );

        return redirect()->back()->with(
            'success',
            "Hasil seleksi untuk {$pendaftar->nama} berhasil diupdate menjadi '{$request->hasil_seleksi}'."
        );
    }

    public function hapusPengumuman($id)
    {
        $pendaftar = Pendaftaran::findOrFail($id);

        $pengumuman = Pengumuman::where('user_id', $pendaftar->user_id)->first();
        if ($pengumuman) {
            $pengumuman->delete();
            return redirect()->back()->with(
                'success',
                "Pengumuman untuk {$pendaftar->nama} berhasil dihapus."
            );
        }

        return redirect()->back()->with('error', 'Pengumuman tidak ditemukan.');
    }

    public function exportPengumuman(Request $request)
    {
        // Ambil data dengan filter yang sama
        $query = Pendaftaran::with(['user', 'user.berkasPendaftaran'])
            ->where('status_verifikasi', 'Terverifikasi');

        // Terapkan filter
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('hasil_seleksi')) {
            if ($request->hasil_seleksi == 'Belum Diumumkan') {
                $query->whereDoesntHave('user.pengumuman');
            } else {
                $query->whereHas('user.pengumuman', function ($q) use ($request) {
                    $q->where('hasil_seleksi', $request->hasil_seleksi);
                });
            }
        }

        if ($request->filled('sekolah_asal')) {
            $query->where('sekolah_asal', 'like', '%' . $request->sekolah_asal . '%');
        }

        $pendaftars = $query->latest()->get();

        // Ambil data pengumuman
        $userIds = $pendaftars->pluck('user_id');
        $pengumumans = Pengumuman::whereIn('user_id', $userIds)->get()->keyBy('user_id');

        // Statistik
        $stats = [
            'total_terverifikasi' => Pendaftaran::where('status_verifikasi', 'Terverifikasi')->count(),
            'sudah_diumumkan' => Pengumuman::count(),
            'belum_diumumkan' => Pendaftaran::where('status_verifikasi', 'Terverifikasi')
                ->whereDoesntHave('user.pengumuman')->count(),
            'diterima' => Pengumuman::where('hasil_seleksi', 'Diterima')->count(),
            'tidak_diterima' => Pengumuman::where('hasil_seleksi', 'Tidak Diterima')->count(),
        ];

        $data = [
            'pendaftars' => $pendaftars,
            'pengumumans' => $pengumumans,
            'stats' => $stats,
            'tanggal_export' => now()->format('d F Y'),
            'admin' => Auth::user()->name,
            'filter' => [
                'nama' => $request->nama,
                'hasil_seleksi' => $request->hasil_seleksi,
                'sekolah_asal' => $request->sekolah_asal,
            ]
        ];

        $pdf = Pdf::loadView('admin.pengumuman.export', $data);
        $pdf->setPaper('A4', 'landscape');

        $filename = 'laporan-pengumuman-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }
}
