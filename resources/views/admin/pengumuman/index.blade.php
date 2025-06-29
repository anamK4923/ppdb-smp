@extends('adminlte::page')

@section('title', 'Kelola Hasil Pengumuman')

@section('content_header')
<h1>
    <i class="fas fa-bullhorn"></i> Kelola Hasil Pengumuman
</h1>
@stop

@section('content')
<!-- Statistik Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_terverifikasi'] }}</h3>
                <p>Total Terverifikasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['diterima'] }}</h3>
                <p>Diterima</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['tidak_diterima'] }}</h3>
                <p>Tidak Diterima</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-times"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['belum_diumumkan'] }}</h3>
                <p>Belum Diumumkan</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
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
        <form method="GET" action="{{ route('admin.pengumuman.index') }}" id="filterForm">
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
                        <label>Hasil Seleksi</label>
                        <select name="hasil_seleksi" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="Belum Diumumkan" {{ request('hasil_seleksi') == 'Belum Diumumkan' ? 'selected' : '' }}>
                                Belum Diumumkan
                            </option>
                            <option value="Diterima" {{ request('hasil_seleksi') == 'Diterima' ? 'selected' : '' }}>
                                Diterima
                            </option>
                            <option value="Tidak Diterima" {{ request('hasil_seleksi') == 'Tidak Diterima' ? 'selected' : '' }}>
                                Tidak Diterima
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Sekolah Asal</label>
                        <input type="text" name="sekolah_asal" class="form-control"
                            value="{{ request('sekolah_asal') }}" placeholder="Cari sekolah...">
                    </div>
                </div>
                <div class="col-md-3">
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
                        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset Filter
                        </a>
                        <button type="button" class="btn btn-success" onclick="exportPdf()">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Aksi Batch -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-tasks"></i> Aksi Batch
        </h3>
    </div>
    <div class="card-body">
        <form id="batchForm" method="POST" action="{{ route('admin.pengumuman.batch') }}">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Hasil Seleksi <span class="text-danger">*</span></label>
                        <select id="batchHasilSeleksi" name="hasil_seleksi" class="form-control" required>
                            <option value="">Pilih Hasil Seleksi</option>
                            <option value="Diterima">Diterima</option>
                            <option value="Tidak Diterima">Tidak Diterima</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tanggal Pengumuman <span class="text-danger">*</span></label>
                        <input id="batchTanggalPengumuman" type="date" name="tanggal_pengumuman" class="form-control"
                            value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button type="button" class="btn btn-warning btn-block" onclick="updateBatch()">
                                <i class="fas fa-save"></i> Update Terpilih
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Pilih pendaftar dengan mencentang checkbox, lalu pilih hasil seleksi dan tanggal pengumuman.
                    </small>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Pengumuman -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i> Daftar Pendaftar Terverifikasi
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
                        <th width="3%">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>NISN</th>
                        <th>Sekolah Asal</th>
                        <th>Status Pengumuman</th>
                        <th>Tanggal Pengumuman</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftars as $index => $pendaftar)
                    <tr>
                        <td>
                            <input type="checkbox" name="pendaftar_ids[]" value="{{ $pendaftar->id }}" class="pendaftar-checkbox">
                        </td>
                        <td>{{ $pendaftars->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $pendaftar->nama }}</strong>
                            <br>
                            <small class="text-muted">{{ $pendaftar->user->email }}</small>
                        </td>
                        <td>{{ $pendaftar->nisn ?? '-' }}</td>
                        <td>{{ $pendaftar->sekolah_asal ?? '-' }}</td>
                        <td>
                            @if(isset($pengumumans[$pendaftar->user_id]))
                            <span class="badge badge-{{ $pengumumans[$pendaftar->user_id]->hasil_seleksi == 'Diterima' ? 'success' : 'danger' }} badge-lg">
                                {{ $pengumumans[$pendaftar->user_id]->hasil_seleksi }}
                            </span>
                            @else
                            <span class="badge badge-warning badge-lg">Belum Diumumkan</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($pengumumans[$pendaftar->user_id]))
                            {{ \Carbon\Carbon::parse($pengumumans[$pendaftar->user_id]->tanggal)->format('d/m/Y') }}
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <!-- Tombol Update Individual -->
                                <button type="button" class="btn btn-primary btn-sm"
                                    data-toggle="modal"
                                    data-target="#modalUpdate{{ $pendaftar->id }}"
                                    title="Update Hasil">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Tombol Hapus Pengumuman -->
                                <!-- @if(isset($pengumumans[$pendaftar->user_id]))
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete('{{ $pendaftar->id }}')"
                                    title="Hapus Pengumuman">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif -->
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Update Individual -->
                    <div class="modal fade" id="modalUpdate{{ $pendaftar->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.pengumuman.update', $pendaftar->id) }}">
                                    @csrf
                                    <div class="modal-header">
                                        <h4 class="modal-title">Update Hasil Seleksi - {{ $pendaftar->nama }}</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Hasil Seleksi <span class="text-danger">*</span></label>
                                            <select name="hasil_seleksi" class="form-control" required>
                                                <option value="">Pilih Hasil Seleksi</option>
                                                <option value="Diterima" {{ isset($pengumumans[$pendaftar->user_id]) && $pengumumans[$pendaftar->user_id]->hasil_seleksi == 'Diterima' ? 'selected' : '' }}>
                                                    Diterima
                                                </option>
                                                <option value="Tidak Diterima" {{ isset($pengumumans[$pendaftar->user_id]) && $pengumumans[$pendaftar->user_id]->hasil_seleksi == 'Tidak Diterima' ? 'selected' : '' }}>
                                                    Tidak Diterima
                                                </option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Pengumuman <span class="text-danger">*</span></label>
                                            <input type="date" name="tanggal_pengumuman" class="form-control"
                                                value="{{ isset($pengumumans[$pendaftar->user_id]) ? $pengumumans[$pendaftar->user_id]->tanggal : date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <i class="fas fa-inbox"></i> Tidak ada data pendaftar terverifikasi
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

    .badge-lg {
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }

    .pendaftar-checkbox {
        transform: scale(1.2);
    }
</style>
@stop

@section('js')
<script>
    // Select All Checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.pendaftar-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Update Batch Function (Alternative dengan ID)
    function updateBatch() {
        const checkedBoxes = document.querySelectorAll('.pendaftar-checkbox:checked');

        if (checkedBoxes.length === 0) {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Pilih minimal satu pendaftar untuk diupdate.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Gunakan getElementById untuk lebih spesifik
        const hasilSeleksi = document.getElementById('batchHasilSeleksi').value;
        const tanggalPengumuman = document.getElementById('batchTanggalPengumuman').value;

        // Debug: tampilkan nilai yang didapat
        console.log('Hasil Seleksi:', hasilSeleksi);
        console.log('Tanggal Pengumuman:', tanggalPengumuman);
        console.log('Checked boxes:', checkedBoxes.length);

        if (!hasilSeleksi || !tanggalPengumuman) {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Pilih hasil seleksi dan tanggal pengumuman terlebih dahulu.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Update',
            text: `Apakah Anda yakin ingin mengupdate ${checkedBoxes.length} pendaftar menjadi "${hasilSeleksi}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('batchForm');

                // Remove existing hidden inputs
                const existingInputs = form.querySelectorAll('input[type="hidden"][name="pendaftar_ids[]"]');
                existingInputs.forEach(input => input.remove());

                // Add checked IDs
                checkedBoxes.forEach(checkbox => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'pendaftar_ids[]';
                    hiddenInput.value = checkbox.value;
                    form.appendChild(hiddenInput);
                });

                form.submit();
            }
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Pengumuman akan dihapus dan status kembali ke 'Belum Diumumkan'!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = `/admin/pengumuman/${id}/hapus`;
                form.submit();
            }
        });
    }

    function exportPdf() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        window.open(`{{ route('admin.pengumuman.export') }}?${params.toString()}`, '_blank');
    }

    // Auto hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@stop