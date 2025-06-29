@extends('adminlte::page')

@section('title', 'Kelola Data Pendaftar')

@section('content_header')
<h1>
    <i class="fas fa-users"></i> Kelola Data Pendaftar
</h1>
@stop

@section('content')
<!-- Statistik Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Pendaftar</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
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
        </div>
    </div>
</div>

<!-- Info Notice -->
<div class="alert alert-info">
    <h5><i class="icon fas fa-info"></i> Informasi!</h5>
    Menu ini khusus untuk <strong>verifikasi data pendaftar</strong>.
    Untuk mengatur <strong>hasil seleksi</strong>, silakan gunakan menu
    <a href="{{ route('admin.pengumuman.index') }}" class="alert-link">
        <i class="fas fa-bullhorn"></i> Kelola Hasil Pengumuman
    </a>.
</div>

<!-- Filter dan Pencarian -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter"></i> Filter Data
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pendaftar.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Nama Pendaftar</label>
                        <input type="text" name="nama" class="form-control"
                            value="{{ request('nama') }}" placeholder="Cari nama...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status Verifikasi</label>
                        <select name="status_verifikasi" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="Belum Diverifikasi" {{ request('status_verifikasi') == 'Belum Diverifikasi' ? 'selected' : '' }}>
                                Belum Diverifikasi
                            </option>
                            <option value="Terverifikasi" {{ request('status_verifikasi') == 'Terverifikasi' ? 'selected' : '' }}>
                                Terverifikasi
                            </option>
                            <option value="Ditolak" {{ request('status_verifikasi') == 'Ditolak' ? 'selected' : '' }}>
                                Ditolak
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tanggal Dari</label>
                        <input type="date" name="tanggal_dari" class="form-control"
                            value="{{ request('tanggal_dari') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tanggal Sampai</label>
                        <input type="date" name="tanggal_sampai" class="form-control"
                            value="{{ request('tanggal_sampai') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group">
                        <a href="{{ route('admin.pendaftar.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset Filter
                        </a>
                        <button type="button" class="btn btn-success" onclick="exportPdf()">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </button>
                        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-warning">
                            <i class="fas fa-bullhorn"></i> Kelola Pengumuman
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Pendaftar -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i> Daftar Pendaftar
        </h3>
        <div class="card-tools">
            <span class="badge badge-info">
                Total: {{ $pendaftars->total() }} data
            </span>
        </div>
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

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>NISN</th>
                        <th>Sekolah Asal</th>
                        <th>Status Verifikasi</th>
                        <th>Tanggal Daftar</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftars as $index => $pendaftar)
                    <tr>
                        <td>{{ $pendaftars->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $pendaftar->nama }}</strong>
                            <br>
                            <small class="text-muted">{{ $pendaftar->user->email }}</small>
                        </td>
                        <td>{{ $pendaftar->nisn ?? '-' }}</td>
                        <td>{{ $pendaftar->sekolah_asal ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $pendaftar->status_verifikasi_badge }}">
                                {{ $pendaftar->status_verifikasi }}
                            </span>
                        </td>
                        <td>{{ $pendaftar->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <!-- Tombol Lihat Detail -->
                                <a href="{{ route('admin.pendaftar.show', $pendaftar->id) }}"
                                    class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Tombol Verifikasi -->
                                <button type="button" class="btn btn-warning btn-sm"
                                    data-toggle="modal"
                                    data-target="#modalVerifikasi{{ $pendaftar->id }}"
                                    title="Verifikasi">
                                    <i class="fas fa-check"></i>
                                </button>

                                <!-- Tombol Cetak PDF -->
                                <!-- <a href="{{ route('admin.pendaftar.cetak', $pendaftar->id) }}"
                                    class="btn btn-success btn-sm"
                                    target="_blank" title="Cetak PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a> -->

                                <!-- Tombol Hapus -->
                                <!-- <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete('{{ $pendaftar->id }}')"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button> -->
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Verifikasi (Tanpa Hasil Seleksi) -->
                    <div class="modal fade" id="modalVerifikasi{{ $pendaftar->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.pendaftar.verifikasi', $pendaftar->id) }}">
                                    @csrf
                                    <div class="modal-header">
                                        <h4 class="modal-title">Verifikasi Data - {{ $pendaftar->nama }}</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Status Verifikasi <span class="text-danger">*</span></label>
                                            <select name="status_verifikasi" class="form-control" required>
                                                <option value="">Pilih Status</option>
                                                <option value="Terverifikasi" {{ $pendaftar->status_verifikasi == 'Terverifikasi' ? 'selected' : '' }}>
                                                    Terverifikasi
                                                </option>
                                                <option value="Ditolak" {{ $pendaftar->status_verifikasi == 'Ditolak' ? 'selected' : '' }}>
                                                    Ditolak
                                                </option>
                                            </select>
                                        </div>

                                        <!-- <div class="form-group">
                                            <label>Catatan</label>
                                            <textarea name="catatan" class="form-control" rows="3"
                                                placeholder="Catatan verifikasi (opsional)"></textarea>
                                        </div> -->

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Catatan:</strong> Hasil seleksi (Diterima/Tidak Diterima) diatur di menu
                                            <a href="{{ route('admin.pengumuman.index') }}">Kelola Hasil Pengumuman</a>.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Verifikasi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <i class="fas fa-inbox"></i> Tidak ada data pendaftar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $pendaftars->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Form Hapus (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@stop

@section('css')
<style>
    .small-box {
        border-radius: 10px;
    }

    .card {
        border-radius: 10px;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .table th {
        background-color: #f8f9fa;
    }
</style>
@stop

@section('js')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pendaftar akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = `/admin/pendaftar/${id}`;
                form.submit();
            }
        });
    }

    function exportPdf() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        window.open(`{{ route('admin.pendaftar.export') }}?${params.toString()}`, '_blank');
    }

    // Auto hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@stop