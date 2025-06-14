<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DataPendaftarController extends Controller
{
    public function index(): View
    {
        $data = collect([
            [
                'id' => 1,
                'nama' => 'Ahmad Fajar',
                'nisn' => '1234567890',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '2010-05-20',
                'no_telpon' => '081234567890',
                'created_at' => '2024-06-10'
            ],
            [
                'id' => 2,
                'nama' => 'Siti Nurhaliza',
                'nisn' => '1234567891',
                'jenis_kelamin' => 'Perempuan',
                'tanggal_lahir' => '2010-08-15',
                'no_telpon' => '081987654321',
                'created_at' => '2024-06-12'
            ],
            [
                'id' => 3,
                'nama' => 'Budi Santoso',
                'nisn' => '1234567892',
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => '2010-11-05',
                'no_telpon' => '085678901234',
                'created_at' => '2024-06-13'
            ],
        ]);

        return view('kelola-pendaftar.kelola-data-pendaftar', compact('data'));
    }
}
