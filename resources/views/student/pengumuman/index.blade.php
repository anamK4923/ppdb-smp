@extends('adminlte::page')

@section('title', 'Pengumuman PPDB')

@section('content_header')
<h1>
    <i class="fas fa-bullhorn"></i> Pengumuman Hasil Seleksi PPDB
</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Pengumuman
                </h3>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('error') }}
                </div>
                @endif

                @if(!$pendaftaran)
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                    Anda belum melengkapi data pendaftaran. Silakan lengkapi terlebih dahulu.
                    <br><br>
                    <a href="{{ route('student.pendaftaran.index') }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Lengkapi Data Pendaftaran
                    </a>
                </div>
                @elseif($pendaftaran->status_verifikasi !== 'Terverifikasi')
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info-circle"></i> Status Verifikasi</h5>
                    Data pendaftaran Anda masih dalam tahap
                    <strong>{{ $pendaftaran->status_verifikasi }}</strong>.
                    <br>
                    Pengumuman akan tersedia setelah data Anda diverifikasi oleh admin.
                </div>
                @elseif(!$pengumumanTersedia)
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-clock"></i> Pengumuman Belum Tersedia</h5>
                    Pengumuman hasil seleksi belum diumumkan.
                    <br>
                    Silakan tunggu pengumuman resmi dari panitia PPDB.
                </div>
                @else
                <div class="alert alert-success">
                    <h5><i class="icon fas fa-check-circle"></i> Pengumuman Tersedia!</h5>
                    Hasil seleksi PPDB sudah dapat dilihat.
                    <br>
                    Klik tombol di bawah untuk melihat hasil seleksi Anda.
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('student.pengumuman.hasil') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-eye"></i> Lihat Hasil Pengumuman
                    </a>
                </div>
                @endif

                @if($pendaftaran)
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informasi Pendaftar</h5>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%">Nama</td>
                                <td>: {{ $pendaftaran->nama }}</td>
                            </tr>
                            <tr>
                                <td>NISN</td>
                                <td>: {{ $pendaftaran->nisn ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Sekolah Asal</td>
                                <td>: {{ $pendaftaran->sekolah_asal ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Status Pendaftaran</h5>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%">Status Verifikasi</td>
                                <td>:
                                    <span class="badge badge-{{ $pendaftaran->status_verifikasi_badge }}">
                                        {{ $pendaftaran->status_verifikasi }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Daftar</td>
                                <td>: {{ $pendaftaran->created_at->format('d F Y') }}</td>
                            </tr>
                            @if($pengumumanTersedia)
                            <tr>
                                <td>Tanggal Pengumuman</td>
                                <td>: {{ $pengumuman->tanggal ? \Carbon\Carbon::parse($pengumuman->tanggal)->format('d F Y') : '-' }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($bisaLihatHasil)
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-download"></i>
                    Unduh Hasil Pengumuman
                </h3>
            </div>
            <div class="card-body">
                <p>Anda dapat mengunduh hasil pengumuman dalam format PDF untuk disimpan atau dicetak.</p>
                <a href="{{ route('student.pengumuman.cetak') }}" class="btn btn-success" target="_blank">
                    <i class="fas fa-file-pdf"></i> Unduh PDF Pengumuman
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@stop

@section('css')
<style>
    .alert {
        border-radius: 10px;
    }

    .card {
        border-radius: 15px;
    }

    .btn-lg {
        padding: 12px 30px;
        font-size: 18px;
        border-radius: 25px;
    }
</style>
@stop