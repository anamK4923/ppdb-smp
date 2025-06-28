<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BerkasPendaftaran;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();
        $berkasPendaftaran = BerkasPendaftaran::where('id_user', $user->id)->first();

        // Hitung progress berdasarkan field yang sudah diisi
        $progress = $this->calculateProgress($pendaftaran, $berkasPendaftaran);

        return view('student.pendaftaran.index', compact('pendaftaran', 'progress', 'berkasPendaftaran'));
    }

    public function store(Request $request)
    {
        // Validasi data biodata
        $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:10',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'sekolah_asal' => 'required|string|max:255',
            'agama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:16',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string|max:255',
            'pekerjaan_ibu' => 'required|string|max:255',
        ]);

        // Validasi file
        $request->validate([
            'kk_file' => 'nullable|file|mimes:pdf|max:2048',
            'akta_file' => 'nullable|file|mimes:pdf|max:2048',
            'piagam_file' => 'nullable|file|mimes:pdf|max:2048',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::find(Auth::id());
        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();
        $berkasPendaftaran = BerkasPendaftaran::where('id_user', $user->id)->first();

        $data = [
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'nik' => $request->nik,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'agama' => $request->agama,
            'sekolah_asal' => $request->sekolah_asal,
            'no_hp' => $request->no_hp,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
        ];

        $dataFile = [];

        // Handle file uploads
        $files = ['kk_file', 'akta_file', 'piagam_file', 'foto'];
        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                // Delete old file if exists
                if ($berkasPendaftaran && $berkasPendaftaran->$file) {
                    Storage::disk('public')->delete($berkasPendaftaran->$file);
                }
                $dataFile[$file] = $request->file($file)->store('dokumen', 'public');
            }
        }

        // Jika update
        if ($pendaftaran || $berkasPendaftaran) {
            if ($pendaftaran) {
                $pendaftaran->update($data);
            }

            if ($berkasPendaftaran) {
                $berkasPendaftaran->update($dataFile);
            }

            // Jika ada file foto baru, update profile_image di tabel users
            if (isset($dataFile['foto'])) {
                $user->update([
                    'profile_image' => $dataFile['foto']
                ]);
            }

            $message = 'Data pendaftaran berhasil diperbarui!';
        } else {
            // Jika create baru
            $data['user_id'] = $user->id;
            $data['status_verifikasi'] = 'Belum Diverifikasi';
            $pendaftaranBaru = Pendaftaran::create($data);

            $dataFile['id_user'] = $user->id;
            BerkasPendaftaran::create($dataFile);

            // Jika ada file foto, update profile_image user
            if (isset($dataFile['foto'])) {
                $user->update([
                    'profile_image' => $dataFile['foto']
                ]);
            }

            $message = 'Data pendaftaran berhasil disimpan!';
        }

        return redirect()->route('student.pendaftaran.index')
            ->with('success', $message);
    }

    private function calculateProgress($pendaftaran, $berkasPendaftaran)
    {
        if (!$pendaftaran) return 0;
        if (!$berkasPendaftaran) return 0;

        $fields = ['nisn', 'nik', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'alamat', 'sekolah_asal', 'agama', 'no_hp', 'nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu'];
        $files = ['kk_file', 'akta_file', 'foto', 'piagam_file'];

        $filledFields = 0;
        $totalFields = count($fields);
        $totalFields = count($fields) + count($files);

        // Check text fields
        foreach ($fields as $field) {
            if (!empty($pendaftaran->$field)) {
                $filledFields++;
            }
        }

        // Check file fields
        foreach ($files as $file) {
            if (!empty($berkasPendaftaran->$file)) {
                $filledFields++;
            }
        }

        return round(($filledFields / $totalFields) * 100);
    }
}
