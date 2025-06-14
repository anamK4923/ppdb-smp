@extends('layouts.app')

@section('subtitle', 'Formulir Pendaftaran Siswa Baru')
@section('content_header_title', 'Formulir Pendaftaran Siswa Baru')

@section('content_body')

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Formulir Pendaftaran Siswa</h3>
    </div>

    <form method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <div class="form-group">
                <label>NISN</label>
                <input type="text" name="nisn" class="form-control" placeholder="Masukkan NISN" required>
            </div>

            <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" placeholder="Masukkan tempat lahir" required>
            </div>

            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
            </div>

            <div class="form-group">
                <label>Agama</label>
                <select name="agama" class="form-control" required>
                    <option value="">Pilih Agama</option>
                    <option value="Islam">Islam</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Katolik">Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Konghucu">Konghucu</option>
                </select>
            </div>

            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="no_telpon" class="form-control" placeholder="Masukkan nomor telepon" required>
            </div>

            <div class="form-group">
                <label>File Ijazah (PDF)</label>
                <div class="custom-file">
                    <input type="file" name="file_ijazah" class="custom-file-input" accept="application/pdf" required>
                    <label class="custom-file-label">Pilih file ijazah</label>
                </div>
            </div>

            <div class="form-group">
                <label>File Kartu Keluarga (PDF)</label>
                <div class="custom-file">
                    <input type="file" name="file_kk" class="custom-file-input" accept="application/pdf" required>
                    <label class="custom-file-label">Pilih file kartu keluarga</label>
                </div>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Daftar</button>
        </div>
    </form>
</div>

@stop