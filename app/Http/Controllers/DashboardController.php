<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function indexAdmin(): View
    {
        // Data dummy
        $data = [
            'totalPendaftar' => 350,
            'sudahVerifikasi' => 210,
            'belumVerifikasi' => 90,
            'ditolak' => 50,
            'chartData' => [12, 19, 3, 5, 2, 3, 9],
            'chartLabels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            'pendaftarTerbaru' => [
                ['nama' => 'Ahmad Fauzi', 'status' => 'Belum Diverifikasi'],
                ['nama' => 'Siti Nurhaliza', 'status' => 'Diverifikasi'],
                ['nama' => 'Rizki Ananda', 'status' => 'Ditolak'],
            ],
        ];

        return view('dashboard.dashboard-admin', $data);
    }

    public function indexStudent(): View
    {
        return view('dashboard.dashboard-student');
    }
}
