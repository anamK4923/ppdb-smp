<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PengumumanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();
        $pengumuman = Pengumuman::where('user_id', $user->id)->first();

        // Cek apakah pengumuman sudah tersedia
        $pengumumanTersedia = $pengumuman !== null;

        // Status untuk menentukan apakah bisa melihat hasil
        $bisaLihatHasil = $pendaftaran &&
            $pendaftaran->status_verifikasi === 'Terverifikasi' &&
            $pengumumanTersedia;

        return view('student.pengumuman.index', compact(
            'pendaftaran',
            'pengumuman',
            'pengumumanTersedia',
            'bisaLihatHasil',
            'user'
        ));
    }

    public function lihatHasil()
    {
        $user = Auth::user();
        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();
        $pengumuman = Pengumuman::where('user_id', $user->id)->first();

        if (!$pendaftaran || !$pengumuman) {
            return redirect()->route('student.pengumuman.index')
                ->with('error', 'Pengumuman belum tersedia.');
        }

        if ($pendaftaran->status_verifikasi !== 'Terverifikasi') {
            return redirect()->route('student.pengumuman.index')
                ->with('error', 'Data Anda belum diverifikasi.');
        }

        return view('student.pengumuman.hasil', compact(
            'pendaftaran',
            'pengumuman',
            'user'
        ));
    }

    public function cetakHasil()
    {
        $user = Auth::user();
        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();
        $pengumuman = Pengumuman::where('user_id', $user->id)->first();

        if (!$pendaftaran || !$pengumuman) {
            return redirect()->route('student.pengumuman.index')
                ->with('error', 'Pengumuman belum tersedia.');
        }

        if ($pendaftaran->status_verifikasi !== 'Terverifikasi') {
            return redirect()->route('student.pengumuman.index')
                ->with('error', 'Data Anda belum diverifikasi.');
        }

        $data = [
            'user' => $user,
            'pendaftaran' => $pendaftaran,
            'pengumuman' => $pengumuman,
            'tanggal_cetak' => now()->format('d F Y')
        ];

        $pdf = Pdf::loadView('student.pengumuman.cetak', $data);

        return $pdf->download('pengumuman-ppdb-' . $pendaftaran->nama . '.pdf');
    }
}
