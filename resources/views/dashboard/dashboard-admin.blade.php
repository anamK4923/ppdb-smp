@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
<div class="row">
    <div class="col-sm-6">
        <h1>Dashboard Admin</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<!-- Statistik Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_pendaftar'] }}</h3>
                <p>Total Pendaftar</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.pendaftar.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['terverifikasi'] }}</h3>
                <p>Terverifikasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('admin.pendaftar.index', ['status_verifikasi' => 'Terverifikasi']) }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['belum_verifikasi'] }}</h3>
                <p>Belum Diverifikasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('admin.pendaftar.index', ['status_verifikasi' => 'Belum Diverifikasi']) }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['ditolak'] }}</h3>
                <p>Ditolak</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <a href="{{ route('admin.pendaftar.index', ['status_verifikasi' => 'Ditolak']) }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Statistik Pengumuman -->
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $stats['diterima'] }}</h3>
                <p>Diterima</p>
            </div>
            <div class="icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <a href="{{ route('admin.pengumuman.index', ['hasil_seleksi' => 'Diterima']) }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $stats['tidak_diterima'] }}</h3>
                <p>Tidak Diterima</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-times"></i>
            </div>
            <a href="{{ route('admin.pengumuman.index', ['hasil_seleksi' => 'Tidak Diterima']) }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-dark">
            <div class="inner">
                <h3>{{ $stats['total_users'] }}</h3>
                <p>Total Users</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-cog"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                Kelola Users <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Pendaftaran -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Grafik Pendaftaran (7 Hari Terakhir)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="pendaftaranChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart Status Verifikasi -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Status Verifikasi</h3>
            </div>
            <div class="card-body">
                <canvas id="verifikasiChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pendaftar Terbaru -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pendaftar Terbaru</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.pendaftar.index') }}" class="btn btn-tool">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="users-list clearfix">
                    @forelse($pendaftarTerbaru as $pendaftar)
                    <li>
                        <img src="{{ $pendaftar->user->profile_image }}" alt="User Image">
                        <a class="users-list-name" href="{{ route('admin.pendaftar.show', $pendaftar->id) }}">
                            {{ $pendaftar->nama }}
                        </a>
                        <span class="users-list-date">{{ $pendaftar->created_at->diffForHumans() }}</span>
                        <span class="badge badge-{{ $pendaftar->status_verifikasi_badge }}">
                            {{ $pendaftar->status_verifikasi }}
                        </span>
                    </li>
                    @empty
                    <li class="text-center p-3">Belum ada pendaftar</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aktivitas Terbaru</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @forelse($aktivitasTerbaru as $aktivitas)
                    <div class="time-label">
                        <span class="bg-{{ $aktivitas['color'] }}">{{ $aktivitas['time']->diffForHumans() }}</span>
                    </div>
                    <div>
                        <i class="{{ $aktivitas['icon'] }} bg-{{ $aktivitas['color'] }}"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">{{ $aktivitas['message'] }}</h3>
                        </div>
                    </div>
                    @empty
                    <div class="text-center">Belum ada aktivitas</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('admin.pendaftar.index', ['status_verifikasi' => 'Belum Diverifikasi']) }}" class="btn btn-warning btn-block">
                            <i class="fas fa-clock"></i> Verifikasi Pending ({{ $stats['belum_verifikasi'] }})
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-bullhorn"></i> Kelola Pengumuman
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-block">
                            <i class="fas fa-user-plus"></i> Tambah User
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.pendaftar.export') }}" class="btn btn-info btn-block">
                            <i class="fas fa-download"></i> Export Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    // Chart Pendaftaran per Hari
    const ctx1 = document.getElementById('pendaftaranChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {
                !!json_encode($chartLabels) !!
            },
            datasets: [{
                label: 'Pendaftaran',
                data: {
                    !!json_encode($chartData) !!
                },
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Chart Status Verifikasi
    const ctx2 = document.getElementById('verifikasiChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {
                !!json_encode($verifikasiChart['labels']) !!
            },
            datasets: [{
                data: {
                    !!json_encode($verifikasiChart['data']) !!
                },
                backgroundColor: {
                    !!json_encode($verifikasiChart['colors']) !!
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@stop

@section('css')
<style>
    .users-list>li {
        width: 25%;
        float: left;
        padding: 10px;
        text-align: center;
    }

    .users-list>li img {
        border-radius: 50%;
        max-width: 60px;
        height: 60px;
    }

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
</style>
@stop