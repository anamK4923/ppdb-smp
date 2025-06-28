@extends('adminlte::page')

@section('title', 'Status & Progress')

@section('content_header')
<h1><i class="fas fa-chart-line text-info"></i> Status & Progress Pendaftaran</h1>
@stop

@section('content')
<div class="row">
    {{-- Status Cards --}}
    <div class="col-md-6">
        <div class="card card-widget widget-user">
            <div class="widget-user-header bg-info">
                <h3 class="widget-user-username">{{ Auth::user()->name }}</h3>
                <h5 class="widget-user-desc">Calon Siswa SMP Al-Irsyad</h5>
            </div>
            <div class="widget-user-image">
                <img class="img-circle elevation-2" src="{{ $user->profile_image ?  $user->profile_image: asset('images/ame.jpg') }}" alt="User Avatar">
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-6 border-right">
                        <div class="description-block">
                            <h5 class="description-header">
                                @if($pendaftaran)
                                <span class="badge badge-{{ $pendaftaran->status_verifikasi == 'Terverifikasi' ? 'success' : ($pendaftaran->status_verifikasi == 'Ditolak' ? 'danger' : 'warning') }}">
                                    {{ $pendaftaran->status_verifikasi }}
                                </span>
                                @else
                                <span class="badge badge-secondary">Belum Daftar</span>
                                @endif
                            </h5>
                            <span class="description-text">STATUS VERIFIKASI</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="description-block">
                            <h5 class="description-header">
                                @if($pendaftaran->status_seleksi)
                                <span class="badge badge-{{ $pendaftaran->status_seleksi == 'Diterima' ? 'success' : ($pendaftaran->status_seleksi == 'Tidak Diterima' ? 'danger' : 'secondary') }}">
                                    {{ $pendaftaran->status_seleksi }}
                                </span>
                                @else
                                <span class="badge badge-secondary">Belum Diproses</span>
                                @endif
                            </h5>
                            <span class="description-text">STATUS SELEKSI</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bolt"></i> Quick Actions</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('student.pendaftaran.index') }}" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-edit"></i> Lengkapi Data Pendaftaran
                </a>
                <button class="btn btn-info btn-block mb-2" onclick="showComingSoon()">
                    <i class="fas fa-download"></i> Download Bukti Pendaftaran
                </button>
                <button class="btn btn-warning btn-block" onclick="showComingSoon()">
                    <i class="fas fa-print"></i> Cetak Kartu Ujian
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Timeline Progress --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-timeline"></i> Timeline Progress PPDB</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($timeline as $index => $step)
                    <div class="time-label">
                        <span class="bg-{{ $step['status'] == 'completed' ? 'success' : ($step['status'] == 'pending' ? 'warning' : ($step['status'] == 'rejected' ? 'danger' : 'secondary')) }}">
                            {{ $step['date'] ?? 'Belum Diproses' }}
                        </span>
                    </div>

                    <div>
                        <i class="{{ $step['icon'] }} bg-{{ $step['status'] == 'completed' ? 'success' : ($step['status'] == 'pending' ? 'warning' : ($step['status'] == 'rejected' ? 'danger' : 'secondary')) }}"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">
                                {{ $step['title'] }}
                                @if($step['status'] == 'completed')
                                <span class="badge badge-success ml-2">Selesai</span>
                                @elseif($step['status'] == 'pending')
                                <span class="badge badge-warning ml-2">Sedang Proses</span>
                                @elseif($step['status'] == 'rejected')
                                <span class="badge badge-danger ml-2">Ditolak</span>
                                @else
                                <span class="badge badge-secondary ml-2">Menunggu</span>
                                @endif
                            </h3>
                            <div class="timeline-body">
                                {{ $step['description'] }}

                                @if($step['status'] == 'rejected' && $loop->index == 2)
                                <div class="alert alert-danger mt-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Alasan Penolakan:</strong>
                                    {{ $pendaftaran->catatan_admin ?? 'Silakan hubungi admin untuk informasi lebih lanjut.' }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Coming Soon Modal --}}
<div class="modal fade" id="comingSoonModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title text-white">
                    <i class="fas fa-rocket"></i> Fitur Segera Hadir!
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-tools fa-3x text-warning"></i>
                </div>
                <h5>Fitur ini sedang dalam pengembangan</h5>
                <p class="text-muted">Kami sedang bekerja keras untuk menghadirkan fitur terbaik untuk Anda. Mohon bersabar ya! 🚀</p>

                <div class="alert alert-info">
                    <strong>Yang akan datang:</strong>
                    <ul class="list-unstyled mt-2 mb-0">
                        <li><i class="fas fa-check text-success"></i> Download bukti pendaftaran</li>
                        <li><i class="fas fa-check text-success"></i> Cetak kartu ujian</li>
                        <li><i class="fas fa-check text-success"></i> Notifikasi real-time</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <i class="fas fa-thumbs-up"></i> Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    function showComingSoon() {
        $('#comingSoonModal').modal('show');
    }
</script>
@stop