<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use App\Models\BerkasPendaftaran;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'Admin PPDB',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'role' => 'admin',
            'password' => bcrypt('Admin123'),
        ]);

        // Create Student User
        $student = User::factory()->create([
            'name' => 'Siswa PPDB',
            'email' => 'siswa@gmail.com',
            'username' => 'student',
            'role' => 'student',
            'password' => bcrypt('Siswa123'),
        ]);

        // Create sample students with complete data
        $students = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@gmail.com',
                'username' => 'ahmad_fauzi',
                'pendaftaran' => [
                    'nama' => 'Ahmad Fauzi',
                    'nisn' => '1234567890',
                    'nik' => '3201234567890123',
                    'tempat_lahir' => 'Jakarta',
                    'tgl_lahir' => '2010-05-15',
                    'jenis_kelamin' => 'Laki-laki',
                    'agama' => 'Islam',
                    'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                    'sekolah_asal' => 'SD Negeri 01 Jakarta',
                    'nama_ayah' => 'Budi Santoso',
                    'nama_ibu' => 'Siti Aminah',
                    'pekerjaan_ayah' => 'Pegawai Swasta',
                    'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                    'no_hp' => '081234567890',
                    'status_verifikasi' => 'Terverifikasi',
                ],
                'pengumuman' => [
                    'hasil_seleksi' => 'Diterima',
                    'tanggal' => '2024-07-01',
                ]
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@gmail.com',
                'username' => 'siti_nurhaliza',
                'pendaftaran' => [
                    'nama' => 'Siti Nurhaliza',
                    'nisn' => '1234567891',
                    'nik' => '3201234567890124',
                    'tempat_lahir' => 'Bandung',
                    'tgl_lahir' => '2010-08-20',
                    'jenis_kelamin' => 'Perempuan',
                    'agama' => 'Islam',
                    'alamat' => 'Jl. Sudirman No. 456, Bandung',
                    'sekolah_asal' => 'SD Negeri 02 Bandung',
                    'nama_ayah' => 'Andi Wijaya',
                    'nama_ibu' => 'Rina Sari',
                    'pekerjaan_ayah' => 'Guru',
                    'pekerjaan_ibu' => 'Perawat',
                    'no_hp' => '081234567891',
                    'status_verifikasi' => 'Terverifikasi',
                ],
                'pengumuman' => [
                    'hasil_seleksi' => 'Diterima',
                    'tanggal' => '2024-07-01',
                ]
            ],
            [
                'name' => 'Rizki Ananda',
                'email' => 'rizki.ananda@gmail.com',
                'username' => 'rizki_ananda',
                'pendaftaran' => [
                    'nama' => 'Rizki Ananda',
                    'nisn' => '1234567892',
                    'nik' => '3201234567890125',
                    'tempat_lahir' => 'Surabaya',
                    'tgl_lahir' => '2010-12-10',
                    'jenis_kelamin' => 'Laki-laki',
                    'agama' => 'Islam',
                    'alamat' => 'Jl. Pahlawan No. 789, Surabaya',
                    'sekolah_asal' => 'SD Negeri 03 Surabaya',
                    'nama_ayah' => 'Dedi Kurniawan',
                    'nama_ibu' => 'Maya Sari',
                    'pekerjaan_ayah' => 'Wiraswasta',
                    'pekerjaan_ibu' => 'Pegawai Bank',
                    'no_hp' => '081234567892',
                    'status_verifikasi' => 'Belum Diverifikasi',
                ]
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@gmail.com',
                'username' => 'dewi_lestari',
                'pendaftaran' => [
                    'nama' => 'Dewi Lestari',
                    'nisn' => '1234567893',
                    'nik' => '3201234567890126',
                    'tempat_lahir' => 'Yogyakarta',
                    'tgl_lahir' => '2010-03-25',
                    'jenis_kelamin' => 'Perempuan',
                    'agama' => 'Islam',
                    'alamat' => 'Jl. Malioboro No. 321, Yogyakarta',
                    'sekolah_asal' => 'SD Negeri 04 Yogyakarta',
                    'nama_ayah' => 'Hendra Pratama',
                    'nama_ibu' => 'Lina Wati',
                    'pekerjaan_ayah' => 'Dokter',
                    'pekerjaan_ibu' => 'Guru',
                    'no_hp' => '081234567893',
                    'status_verifikasi' => 'Ditolak',
                ]
            ]
        ];

        foreach ($students as $studentData) {
            $user = User::factory()->create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'username' => $studentData['username'],
                'role' => 'student',
                'password' => bcrypt('Student123'),
            ]);

            $pendaftaran = Pendaftaran::create(array_merge(
                $studentData['pendaftaran'],
                ['user_id' => $user->id]
            ));

            // Create berkas pendaftaran (dummy)
            BerkasPendaftaran::create([
                'id_user' => $user->id,
                // Files will be null for demo purposes
            ]);

            // Create pengumuman if exists
            if (isset($studentData['pengumuman'])) {
                Pengumuman::create(array_merge(
                    $studentData['pengumuman'],
                    ['user_id' => $user->id]
                ));
            }
        }
    }
}
