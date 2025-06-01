@extends('layouts.app')

@section('subtitle', 'Dashboard Admin')
@section('content_header_title', 'Dashboard')
@section('content_header_subtitle', 'PPDB - Admin Panel')

@section('content_body')

<div class="row">
    <div class="col-md-3">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalPendaftar }}</h3>
                <p>Total Pendaftar</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $sudahVerifikasi }}</h3>
                <p>Diverifikasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
            <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $belumVerifikasi }}</h3>
                <p>Belum Diverifikasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-clock"></i>
            </div>
            <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $ditolak }}</h3>
                <p>Ditolak</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-times"></i>
            </div>
            <a href="#" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        {{-- Chart Pendaftaran --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Grafik Pendaftaran Mingguan</h3>
            </div>
            <div class="card-body">
                <canvas id="pendaftaranChart" style="min-height: 250px; height: 250px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        {{-- List Pendaftar Terbaru --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pendaftar Terbaru</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach ($pendaftarTerbaru as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $item['nama'] }}
                        <span class="badge 
                                    @if($item['status'] == 'Diverifikasi') bg-success 
                                    @elseif($item['status'] == 'Belum Diverifikasi') bg-warning 
                                    @else bg-danger 
                                    @endif">
                            {{ $item['status'] }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>


@stop

@push('js')
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
<script>
    const ctx = document.getElementById('pendaftaranChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Jumlah Pendaftar',
                data: @json($chartData),
                borderColor: 'rgba(60,141,188,1)',
                backgroundColor: 'rgba(60,141,188,0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush