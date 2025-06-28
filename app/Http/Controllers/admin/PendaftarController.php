<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use App\Models\BerkasPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PendaftarController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with('user');

        // Filter berdasarkan nama
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan status verifikasi
        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        // Filter berdasarkan tanggal pendaftaran
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $pendaftars = $query->latest()->paginate(10);

        // Statistik untuk cards
        $stats = [
            'total' => Pendaftaran::count(),
            'terverifikasi' => Pendaftaran::where('status_verifikasi', 'Terverifikasi')->count(),
            'belum_verifikasi' => Pendaftaran::where('status_verifikasi', 'Belum Diverifikasi')->count(),
            'ditolak' => Pendaftaran::where('status_verifikasi', 'Ditolak')->count(),
        ];

        return view('admin.pendaftar.index', compact('pendaftars', 'stats'));
    }

    public function show($id)
    {
        $pendaftar = Pendaftaran::with(['user', 'user.berkasPendaftaran'])->findOrFail($id);
        $pengumuman = Pengumuman::where('user_id', $pendaftar->user_id)->first();

        return view('admin.pendaftar.show', compact('pendaftar', 'pengumuman'));
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:Terverifikasi,Ditolak',
            'catatan' => 'nullable|string|max:500'
        ]);

        $pendaftar = Pendaftaran::findOrFail($id);

        // Update status verifikasi saja, hasil seleksi dikelola di menu pengumuman
        $pendaftar->update([
            'status_verifikasi' => $request->status_verifikasi,
        ]);

        return redirect()->back()->with('success', 'Status verifikasi berhasil diupdate.');
    }

    public function cetak($id)
    {
        $pendaftar = Pendaftaran::with(['user', 'user.berkasPendaftaran'])->findOrFail($id);
        $pengumuman = Pengumuman::where('user_id', $pendaftar->user_id)->first();

        $data = [
            'pendaftar' => $pendaftar,
            'pengumuman' => $pengumuman,
            'tanggal_cetak' => now()->format('d F Y'),
            'admin' => Auth::user()->name
        ];

        $pdf = Pdf::loadView('admin.pendaftar.cetak', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('data-pendaftar-' . str_replace(' ', '-', $pendaftar->nama) . '.pdf');
    }

    public function exportPdf(Request $request)
    {
        // Ambil data dengan filter yang sama seperti di index
        $query = Pendaftaran::with(['user', 'user.berkasPendaftaran']);

        // Terapkan filter yang sama
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $pendaftars = $query->latest()->get();

        // Ambil data pengumuman untuk semua pendaftar
        $userIds = $pendaftars->pluck('user_id');
        $pengumumans = Pengumuman::whereIn('user_id', $userIds)->get()->keyBy('user_id');

        // Statistik
        $stats = [
            'total' => $pendaftars->count(),
            'terverifikasi' => $pendaftars->where('status_verifikasi', 'Terverifikasi')->count(),
            'belum_verifikasi' => $pendaftars->where('status_verifikasi', 'Belum Diverifikasi')->count(),
            'ditolak' => $pendaftars->where('status_verifikasi', 'Ditolak')->count(),
            'diterima' => $pengumumans->where('hasil_seleksi', 'Diterima')->count(),
            'tidak_diterima' => $pengumumans->where('hasil_seleksi', 'Tidak Diterima')->count(),
        ];

        $data = [
            'pendaftars' => $pendaftars,
            'pengumumans' => $pengumumans,
            'stats' => $stats,
            'tanggal_export' => now()->format('d F Y'),
            'admin' => Auth::user()->name,
            'filter' => [
                'nama' => $request->nama,
                'status_verifikasi' => $request->status_verifikasi,
                'tanggal_dari' => $request->tanggal_dari,
                'tanggal_sampai' => $request->tanggal_sampai,
            ]
        ];

        $pdf = Pdf::loadView('admin.pendaftar.export', $data);
        $pdf->setPaper('A4', 'landscape'); // Landscape untuk tabel yang lebar

        $filename = 'laporan-pendaftar-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    public function destroy($id)
    {
        $pendaftar = Pendaftaran::findOrFail($id);
        $nama = $pendaftar->nama;

        // Hapus pengumuman terkait jika ada
        Pengumuman::where('user_id', $pendaftar->user_id)->delete();

        // Hapus berkas pendaftaran jika ada
        if ($pendaftar->user->berkasPendaftaran) {
            $berkas = $pendaftar->user->berkasPendaftaran;

            // Hapus file dari storage
            // if ($berkas->kk_file) {
            //     \Storage::disk('public')->delete($berkas->kk_file);
            // }
            // if ($berkas->akta_file) {
            //     \Storage::disk('public')->delete($berkas->akta_file);
            // }
            // if ($berkas->piagam_file) {
            //     \Storage::disk('public')->delete($berkas->piagam_file);
            // }
            // if ($berkas->foto) {
            //     \Storage::disk('public')->delete($berkas->foto);
            // }

            $berkas->delete();
        }

        $pendaftar->delete();

        return redirect()->route('admin.pendaftar.index')
            ->with('success', 'Data pendaftar "' . $nama . '" berhasil dihapus.');
    }
}
