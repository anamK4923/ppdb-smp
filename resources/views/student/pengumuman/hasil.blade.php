@extends('adminlte::page')

@section('title', 'Hasil Pengumuman PPDB')

@section('content_header')
<h1>
    <i class="fas fa-certificate"></i> Hasil Pengumuman Seleksi PPDB
</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header text-center bg-primary">
                <h3 class="card-title">
                    <strong>PENGUMUMAN HASIL SELEKSI PPDB</strong><br>
                    <small>SMP AL-IRSYAD</small>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <!-- Header Pengumuman -->
                        <div class="text-center mb-4">
                            <h4><strong>HASIL SELEKSI PENERIMAAN PESERTA DIDIK BARU</strong></h4>
                            <h5>TAHUN PELAJARAN {{ date('Y') }}/{{ date('Y') + 1 }}</h5>
                            <hr>
                        </div>

                        <!-- Data Peserta -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%"><strong>Nama Lengkap</strong></td>
                                        <td>: {{ $pendaftaran->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>NISN</strong></td>
                                        <td>: {{ $pendaftaran->nisn ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>NIK</strong></td>
                                        <td>: {{ $pendaftaran->nik ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tempat, Tgl Lahir</strong></td>
                                        <td>: {{ $pendaftaran->ttl_lengkap ?? ($pendaftaran->tempat_lahir && $pendaftaran->tgl_lahir ? $pendaftaran->tempat_lahir . ', ' . \Carbon\Carbon::parse($pendaftaran->tgl_lahir)->format('d F Y') : '-') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%"><strong>Jenis Kelamin</strong></td>
                                        <td>: {{ $pendaftaran->jenis_kelamin ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sekolah Asal</strong></td>
                                        <td>: {{ $pendaftaran->sekolah_asal ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. HP</strong></td>
                                        <td>: {{ $pendaftaran->no_hp ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Daftar</strong></td>
                                        <td>: {{ $pendaftaran->created_at->format('d F Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Hasil Seleksi -->
                        <div class="text-center mb-4">
                            <div class="alert {{ $pengumuman->hasil_seleksi == 'Diterima' ? 'alert-success' : 'alert-danger' }} alert-lg">
                                <h3>
                                    <i class="fas {{ $pengumuman->hasil_seleksi == 'Diterima' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    <strong>{{ strtoupper($pengumuman->hasil_seleksi) }}</strong>
                                </h3>
                                @if($pengumuman->hasil_seleksi == 'Diterima')
                                <p class="mb-0">Selamat! Anda diterima sebagai peserta didik baru di SMP Al-Irsyad</p>
                                @else
                                <p class="mb-0">Mohon maaf, Anda belum dapat diterima pada periode ini</p>
                                @endif
                            </div>
                        </div>

                        @if($pengumuman->hasil_seleksi == 'Diterima')
                        <!-- Informasi Selanjutnya -->
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> <strong>Informasi Selanjutnya</strong></h5>
                            <ul class="mb-0">
                                <li>Silakan melakukan daftar ulang pada tanggal yang telah ditentukan</li>
                                <li>Bawa dokumen asli dan fotokopi yang diperlukan</li>
                                <li>Hubungi panitia PPDB untuk informasi lebih lanjut</li>
                                <li>Simpan pengumuman ini sebagai bukti penerimaan</li>
                            </ul>
                        </div>
                        @endif

                        <!-- Footer -->
                        <div class="text-center mt-4">
                            <p><small>Tanggal Pengumuman: {{ \Carbon\Carbon::parse($pengumuman->tanggal)->format('d F Y') }}</small></p>
                            <hr>
                            <p><strong>Panitia PPDB SMP Al-Irsyad</strong></p>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="text-center mt-4">
                            <a href="{{ route('student.pengumuman.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <a href="{{ route('student.pengumuman.cetak') }}" class="btn btn-success ml-2" target="_blank">
                                <i class="fas fa-print"></i> Cetak PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .alert-lg {
        padding: 2rem;
        font-size: 1.2rem;
        border-radius: 15px;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table-borderless td {
        border: none;
        padding: 0.3rem 0.75rem;
    }
</style>
@stop