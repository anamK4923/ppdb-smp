@extends('adminlte::page')

@section('title', 'Detail User')

@section('content_header')
<div class="row">
    <div class="col-sm-6">
        <h1>Detail User</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Kelola Users</a></li>
            <li class="breadcrumb-item active">Detail User</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <!-- Informasi User -->
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                        src="{{ $user->profile_image }}"
                        alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{ $user->name }}</h3>
                <p class="text-muted text-center">
                    @if($user->role == 'admin')
                    <span class="badge badge-success">Administrator</span>
                    @else
                    <span class="badge badge-info">Student</span>
                    @endif
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Email</b> <span class="float-right">{{ $user->email }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Username</b> <span class="float-right">{{ $user->username }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b>
                        <span class="float-right">
                            @if($user->email_verified_at)
                            <span class="badge badge-success">Aktif</span>
                            @else
                            <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item">
                        <b>Terdaftar</b> <span class="float-right">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>

                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-block">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Terkait -->
    <div class="col-md-8">
        @if($user->role == 'student')
        <!-- Data Pendaftaran -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Pendaftaran</h3>
            </div>
            <div class="card-body">
                @if($user->pendaftaran)
                <div class="row">
                    <div class="col-md-6">
                        <strong>NISN:</strong> {{ $user->pendaftaran->nisn ?? '-' }}<br>
                        <strong>NIK:</strong> {{ $user->pendaftaran->nik ?? '-' }}<br>
                        <strong>Tempat, Tanggal Lahir:</strong> {{ $user->pendaftaran->ttl_lengkap ?? '-' }}<br>
                        <strong>Jenis Kelamin:</strong> {{ $user->pendaftaran->jenis_kelamin ?? '-' }}<br>
                        <strong>Agama:</strong> {{ $user->pendaftaran->agama ?? '-' }}<br>
                    </div>
                    <div class="col-md-6">
                        <strong>Sekolah Asal:</strong> {{ $user->pendaftaran->sekolah_asal ?? '-' }}<br>
                        <strong>No. HP:</strong> {{ $user->pendaftaran->no_hp ?? '-' }}<br>
                        <strong>Status Verifikasi:</strong>
                        <span class="badge badge-{{ $user->pendaftaran->status_verifikasi_badge }}">
                            {{ $user->pendaftaran->status_verifikasi }}
                        </span><br>
                        <strong>Alamat:</strong> {{ $user->pendaftaran->alamat ?? '-' }}
                    </div>
                </div>
                @else
                <p class="text-muted">Belum ada data pendaftaran</p>
                @endif
            </div>
        </div>

        <!-- Data Berkas -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Berkas Pendaftaran</h3>
            </div>
            <div class="card-body">
                @if($user->berkasPendaftaran)
                <div class="row">
                    <div class="col-md-6">
                        <strong>Kartu Keluarga:</strong>
                        @if($user->berkasPendaftaran->kk_file)
                        <a href="{{ asset('storage/' . $user->berkasPendaftaran->kk_file) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Lihat
                        </a>
                        @else
                        <span class="text-muted">Belum upload</span>
                        @endif
                        <br><br>

                        <strong>Akta Kelahiran:</strong>
                        @if($user->berkasPendaftaran->akta_file)
                        <a href="{{ asset('storage/' . $user->berkasPendaftaran->akta_file) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Lihat
                        </a>
                        @else
                        <span class="text-muted">Belum upload</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>Piagam/Ijazah:</strong>
                        @if($user->berkasPendaftaran->piagam_file)
                        <a href="{{ asset('storage/' . $user->berkasPendaftaran->piagam_file) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Lihat
                        </a>
                        @else
                        <span class="text-muted">Belum upload</span>
                        @endif
                        <br><br>

                        <strong>Foto:</strong>
                        @if($user->berkasPendaftaran->foto)
                        <a href="{{ asset('storage/' . $user->berkasPendaftaran->foto) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        @else
                        <span class="text-muted">Belum upload</span>
                        @endif
                    </div>
                </div>
                @else
                <p class="text-muted">Belum ada berkas yang diupload</p>
                @endif
            </div>
        </div>

        <!-- Data Pengumuman -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hasil Seleksi</h3>
            </div>
            <div class="card-body">
                @if($user->pengumuman->count() > 0)
                @foreach($user->pengumuman as $pengumuman)
                <div class="alert alert-{{ $pengumuman->hasil_seleksi == 'Diterima' ? 'success' : 'danger' }}">
                    <strong>Hasil:</strong> {{ $pengumuman->hasil_seleksi }}<br>
                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pengumuman->tanggal)->format('d F Y') }}
                </div>
                @endforeach
                @else
                <p class="text-muted">Belum ada pengumuman hasil seleksi</p>
                @endif
            </div>
        </div>
        @else
        <!-- Untuk Admin -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Administrator</h3>
            </div>
            <div class="card-body">
                <p>User ini adalah administrator sistem PPDB SMP Al-Irsyad.</p>
                <p><strong>Hak Akses:</strong></p>
                <ul>
                    <li>Kelola data pendaftar</li>
                    <li>Verifikasi dokumen</li>
                    <li>Kelola pengumuman hasil seleksi</li>
                    <li>Kelola users</li>
                    <li>Export laporan</li>
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@stop