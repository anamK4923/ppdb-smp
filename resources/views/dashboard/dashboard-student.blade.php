@extends('adminlte::page')

@section('title', 'Dashboard Siswa')

@section('content_header')
<div class="row">
    <div class="col-sm-6">
        <h1>Dashboard Siswa</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<!-- Welcome Card -->
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Selamat Datang, {{ $user->name }}!</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{ $user->profile_image }}" alt="Profile" class="img-circle" width="100" height="100">
                    </div>
                    <div class="col-md-9">
                        <h4>Sistem PPDB SMP Al-Irsyad</h4>
                        <p>Selamat datang di sistem Penerimaan Peserta Didik Baru (PPDB) SMP Al-Irsyad.
                            Silakan lengkapi data pendaftaran Anda dan pantau status pendaftaran melalui dashboard ini.</p>

                        @if($progress < 100)
                            <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Perhatian!</strong> Data pendaftaran Anda belum lengkap ({{ $progress }}%).
                            Silakan lengkapi untuk melanjutkan proses seleksi.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Progress Card -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Progress Pendaftaran</h3>
            </div>
            <div class="card-body">
                <div class="progress mb-3">
                    <div class="progress-bar bg-{{ $progress == 100 ? 'success' : ($progress > 50 ? 'warning' : 'danger') }}"
                        role="progressbar" style="width: {{ $progress }}%">
                        {{ $progress }}%
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-4">
                        <div class="description-block">
                            <h5 class="description-header">{{ $progress }}%</h5>
                            <span class="description-text">PROGRESS</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="description-block">
                            <h5 class="description-header">
                                @if($pendaftaran)
                                <span class="badge badge-{{ $pendaftaran->status_verifikasi_badge }}">
                                    {{ $pendaftaran->status_verifikasi }}
                                </span>
                                @else
                                <span class="badge badge-secondary">Belum Daftar</span>
                                @endif
                            </h5>
                            <span class="description-text">STATUS</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="description-block">
                            <h5 class="description-header">
                                @if($pengumuman)
                                <span class="badge badge-{{ $pengumuman->hasil_seleksi == 'Diterima' ? 'success' : 'danger' }}">
                                    {{ $pengumuman->hasil_seleksi }}
                                </span>
                                @else
                                <span class="badge badge-secondary">Belum Ada</span>
                                @endif
                            </h5>
                            <span class="description-text">HASIL</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik PPDB -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik PPDB</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="description-block border-right">
                            <span class="description-percentage text-success">
                                <i class="fas fa-users"></i>
                            </span>
                            <h5 class="description-header">{{ $stats['total_pendaftar'] }}</h5>
                            <span class="description-text">TOTAL PENDAFTAR</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <span class="description-percentage text-info">
                                <i class="fas fa-graduation-cap"></i>
                            </span>
                            <h5 class="description-header">{{ $stats['diterima'] }}</h5>
                            <span class="description-text">DITERIMA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Timeline Pendaftaran</h3>
            </div>
            <div class="card-body">
                <div class="timeline timeline-inverse">
                    @foreach($timeline as $step)
                    <div class="time-label">
                        <span class="bg-{{ $step['status'] == 'completed' ? 'success' : ($step['status'] == 'rejected' ? 'danger' : ($step['status'] == 'pending' ? 'warning' : 'secondary')) }}">
                            {{ $step['date'] ?? 'Menunggu' }}
                        </span>
                    </div>
                    <div>
                        <i class="fas fa-{{ $step['status'] == 'completed' ? 'check' : ($step['status'] == 'rejected' ? 'times' : ($step['status'] == 'pending' ? 'clock' : 'circle')) }} 
                                   bg-{{ $step['status'] == 'completed' ? 'success' : ($step['status'] == 'rejected' ? 'danger' : ($step['status'] == 'pending' ? 'warning' : 'secondary')) }}"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">{{ $step['title'] }}</h3>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notifikasi -->
@if(count($notifikasi) > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Notifikasi Penting</h3>
            </div>
            <div class="card-body">
                @foreach($notifikasi as $notif)
                <div class="alert alert-{{ $notif['type'] }} alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-{{ $notif['type'] == 'success' ? 'check' : ($notif['type'] == 'danger' ? 'ban' : ($notif['type'] == 'warning' ? 'exclamation-triangle' : 'info')) }}"></i>
                        {{ $notif['type'] == 'success' ? 'Selamat!' : ($notif['type'] == 'danger' ? 'Perhatian!' : ($notif['type'] == 'warning' ? 'Peringatan!' : 'Informasi!')) }}
                    </h5>
                    {{ $notif['message'] }}
                    @if($notif['action'])
                    <br><br>
                    <a href="{{ $notif['action'] }}" class="btn btn-{{ $notif['type'] }} btn-sm">
                        {{ $notif['action_text'] }}
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Menu Cepat</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($quickActions as $action)
                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-{{ $action['color'] }}">
                                <i class="{{ $action['icon'] }}"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{ $action['title'] }}</span>
                                <span class="info-box-number">{{ $action['description'] }}</span>
                                <a href="{{ $action['url'] }}" class="btn btn-{{ $action['color'] }} btn-sm mt-2">
                                    Akses <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Kontak -->
<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Informasi Kontak</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong><i class="fas fa-phone mr-1"></i> Telepon</strong>
                        <p class="text-muted">(021) 123-4567</p>
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                        <p class="text-muted">ppdb@smp-alirsyad.sch.id</p>
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
                        <p class="text-muted">Jl. Pendidikan No. 123, Jakarta</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }

    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        left: 31px;
        margin: 0;
        border-radius: 2px;
    }

    .timeline>div {
        margin-bottom: 15px;
        position: relative;
    }

    .timeline>div>.timeline-item {
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        border-radius: 3px;
        margin-top: 0;
        background: #fff;
        color: #444;
        margin-left: 60px;
        margin-right: 15px;
        padding: 0;
        position: relative;
    }

    .timeline>div>.fa,
    .timeline>div>.fas,
    .timeline>div>.far,
    .timeline>div>.fab,
    .timeline>div>.fal,
    .timeline>div>.fad,
    .timeline>div>.svg-inline--fa {
        width: 30px;
        height: 30px;
        font-size: 15px;
        line-height: 30px;
        position: absolute;
        color: #666;
        background: #d2d6de;
        border-radius: 50%;
        text-align: center;
        left: 18px;
        top: 0;
    }

    .time-label>span {
        font-weight: 600;
        color: #fff;
        font-size: 12px;
        padding: 5px 10px;
        position: absolute;
        right: 10px;
        top: 0;
        border-radius: 4px;
    }
</style>
@stop