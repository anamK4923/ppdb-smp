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
<!-- Hidden data for JavaScript -->
<div id="chart-data" style="display: none;"
    data-total-pendaftar="{{ $stats['total_pendaftar'] ?? 0 }}"
    data-terverifikasi="{{ $stats['terverifikasi'] ?? 0 }}"
    data-belum-verifikasi="{{ $stats['belum_verifikasi'] ?? 0 }}"
    data-ditolak="{{ $stats['ditolak'] ?? 0 }}"
    data-chart-labels="{{ implode(',', $chartLabels ?? []) }}"
    data-chart-data="{{ implode(',', $chartData ?? []) }}">
</div>

<!-- Statistik Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_pendaftar'] ?? 0 }}</h3>
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
                <h3>{{ $stats['terverifikasi'] ?? 0 }}</h3>
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
                <h3>{{ $stats['belum_verifikasi'] ?? 0 }}</h3>
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
                <h3>{{ $stats['ditolak'] ?? 0 }}</h3>
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
                <h3>{{ $stats['diterima'] ?? 0 }}</h3>
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
                <h3>{{ $stats['tidak_diterima'] ?? 0 }}</h3>
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
                <h3>{{ $stats['total_users'] ?? 0 }}</h3>
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
                    @if(isset($pendaftarTerbaru) && count($pendaftarTerbaru) > 0)
                    @foreach($pendaftarTerbaru as $pendaftar)
                    <li>
                        <img src="{{ $pendaftar->user->profile_image ?? '/placeholder.svg?height=60&width=60' }}" alt="User Image">
                        <a class="users-list-name" href="{{ route('admin.pendaftar.show', $pendaftar->id) }}">
                            {{ $pendaftar->nama }}
                        </a>
                        <span class="users-list-date">{{ $pendaftar->created_at->diffForHumans() }}</span>
                        <span class="badge badge-{{ $pendaftar->status_verifikasi_badge ?? 'secondary' }}">
                            {{ $pendaftar->status_verifikasi }}
                        </span>
                    </li>
                    @endforeach
                    @else
                    <li class="text-center p-3">Belum ada pendaftar</li>
                    @endif
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
                    @if(isset($aktivitasTerbaru) && count($aktivitasTerbaru) > 0)
                    @foreach($aktivitasTerbaru as $aktivitas)
                    <div class="time-label">
                        <span class="bg-{{ $aktivitas['color'] }}">{{ $aktivitas['time']->diffForHumans() }}</span>
                    </div>
                    <div>
                        <i class="{{ $aktivitas['icon'] }} bg-{{ $aktivitas['color'] }}"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">{{ $aktivitas['message'] }}</h3>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center">Belum ada aktivitas</div>
                    @endif
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
                            <i class="fas fa-clock"></i> Verifikasi Pending ({{ $stats['belum_verifikasi'] ?? 0 }})
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get data from hidden div
        const chartDataElement = document.getElementById('chart-data');
        const totalPendaftar = parseInt(chartDataElement.getAttribute('data-total-pendaftar')) || 0;
        const terverifikasi = parseInt(chartDataElement.getAttribute('data-terverifikasi')) || 0;
        const belumVerifikasi = parseInt(chartDataElement.getAttribute('data-belum-verifikasi')) || 0;
        const ditolak = parseInt(chartDataElement.getAttribute('data-ditolak')) || 0;

        // Get chart data from controller
        const chartLabelsString = chartDataElement.getAttribute('data-chart-labels') || '';
        const chartDataString = chartDataElement.getAttribute('data-chart-data') || '';

        // Parse chart data
        let chartLabels = [];
        let chartData = [];

        if (chartLabelsString && chartDataString) {
            chartLabels = chartLabelsString.split(',').filter(label => label.trim() !== '');
            chartData = chartDataString.split(',').map(data => parseInt(data.trim()) || 0);
        }

        // If no data from controller, create sample data
        if (chartLabels.length === 0 || chartData.length === 0) {
            const today = new Date();
            chartLabels = [];
            chartData = [];

            for (let i = 6; i >= 0; i--) {
                const date = new Date(today);
                date.setDate(date.getDate() - i);
                chartLabels.push(date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short'
                }));
                chartData.push(Math.floor(Math.random() * 5));
            }
        }

        // Data untuk Chart Verifikasi
        const verifikasiLabels = [];
        const verifikasiData = [];
        const verifikasiColors = [];

        if (terverifikasi > 0) {
            verifikasiLabels.push('Terverifikasi');
            verifikasiData.push(terverifikasi);
            verifikasiColors.push('#28a745');
        }
        if (belumVerifikasi > 0) {
            verifikasiLabels.push('Belum Diverifikasi');
            verifikasiData.push(belumVerifikasi);
            verifikasiColors.push('#ffc107');
        }
        if (ditolak > 0) {
            verifikasiLabels.push('Ditolak');
            verifikasiData.push(ditolak);
            verifikasiColors.push('#dc3545');
        }

        // If no data, show placeholder
        if (verifikasiData.length === 0) {
            verifikasiLabels.push('Belum ada data');
            verifikasiData.push(1);
            verifikasiColors.push('#6c757d');
        }

        // Chart Pendaftaran per Hari
        const ctx1 = document.getElementById('pendaftaranChart');
        if (ctx1) {
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Jumlah Pendaftaran',
                        data: chartData,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#007bff',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return Math.floor(value);
                                }
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#007bff',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return 'Pendaftaran: ' + context.parsed.y + ' orang';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Chart Status Verifikasi
        const ctx2 = document.getElementById('verifikasiChart');
        if (ctx2) {
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: verifikasiLabels,
                    datasets: [{
                        data: verifikasiData,
                        backgroundColor: verifikasiColors,
                        borderWidth: 3,
                        borderColor: '#fff',
                        hoverBorderWidth: 5,
                        hoverBorderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
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
        object-fit: cover;
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

    .small-box {
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .small-box:hover {
        transform: translateY(-2px);
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .users-list-name {
        font-weight: 600;
        color: #495057;
        text-decoration: none;
    }

    .users-list-name:hover {
        color: #007bff;
        text-decoration: none;
    }

    .users-list-date {
        font-size: 12px;
        color: #6c757d;
    }

    .badge {
        font-size: 10px;
        margin-top: 5px;
    }

    #chart-data {
        display: none !important;
    }

    .card-body canvas {
        max-height: 300px;
    }
</style>
@stop