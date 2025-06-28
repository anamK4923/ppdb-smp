@extends('adminlte::page')

@section('title', 'Detail Pendaftar')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="m-0 text-dark">Detail Pendaftar</h1>
        <small class="text-muted">Informasi lengkap data pendaftar</small>
    </div>
    <div>
        <a href="{{ route('admin.pendaftar.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <!-- Data Pribadi -->
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user"></i> Data Pribadi
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Nama Lengkap</strong></td>
                                <td>: {{ $pendaftar->nama }}</td>
                            </tr>
                            <tr>
                                <td><strong>NISN</strong></td>
                                <td>: {{ $pendaftar->nisn }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIK</strong></td>
                                <td>: {{ $pendaftar->nik }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Kelamin</strong></td>
                                <td>: {{ $pendaftar->jenis_kelamin }}</td>
                            <tr>
                                <td><strong>Tempat, Tanggal Lahir</strong></td>
                                <td>: {{ $pendaftar->tempat_lahir }}, {{$pendaftar->tgl_lahir->format('d F Y')}}</td>
                            </tr>
                            <tr>
                                <td><strong>Sekolah Asal</strong></td>
                                <td>: {{ $pendaftar->sekolah_asal }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Email</strong></td>
                                <td>: {{ $pendaftar->user->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. HP</strong></td>
                                <td>: {{ $pendaftar->no_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>: {{ $pendaftar->alamat }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Daftar</strong></td>
                                <td>: {{ $pendaftar->created_at->format('d F Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dokumen -->
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-alt"></i> Dokumen Pendukung
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kartu Keluarga</label>
                            <div class="border p-3 rounded">
                                @if($pendaftar->user->berkasPendaftaran->kk_file)
                                <a href="{{ asset('storage/'.$pendaftar->user->berkasPendaftaran->kk_file) }}" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Lihat KK
                                </a>
                                <a href="{{ asset('storage/'.$pendaftar->user->berkasPendaftaran->kk_file) }}" download class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                @else
                                <span class="text-muted">
                                    <i class="fas fa-times-circle text-danger"></i> Belum Upload
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Akta Kelahiran</label>
                            <div class="border p-3 rounded">
                                @if($pendaftar->user->berkasPendaftaran->akta_file)
                                <a href="{{ asset('storage/'.$pendaftar->user->berkasPendaftaran->akta_file) }}" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Lihat Akta
                                </a>
                                <a href="{{ asset('storage/'.$pendaftar->user->berkasPendaftaran->akta_file) }}" download class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                @else
                                <span class="text-muted">
                                    <i class="fas fa-times-circle text-danger"></i> Belum Upload
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Foto Calon Siswa</label>
                            <div class="border p-3 rounded">
                                @if($pendaftar->user->berkasPendaftaran->foto)
                                <a href="{{ asset('storage/'.$pendaftar->user->berkasPendaftaran->foto) }}" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Lihat Rapor
                                </a>
                                <a href="{{ asset('storage/'.$pendaftar->user->berkasPendaftaran->foto) }}" download class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                @else
                                <span class="text-muted">
                                    <i class="fas fa-times-circle text-danger"></i> Belum Upload
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Piagam/Sertifikat</label>
                            <div class="border p-3 rounded">
                                @if($pendaftar->user->berkasPendaftaran->piagam_file)
                                <a href="{{ asset('storage/'.$pendaftar->user->berkasPendaftaran->piagam_file) }}" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Lihat Piagam
                                </a>
                                <a href="{{ asset('storage/'.$pendaftar->user->berkasPendaftaran->piagam_file) }}" download class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                @else
                                <span class="text-muted">
                                    <i class="fas fa-times-circle text-danger"></i> Belum Upload
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status & Verifikasi -->
    <div class="col-md-4">
        <!-- Status Card -->
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i> Status Pendaftaran
                </h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Status Verifikasi</label>
                    <div>
                        @if ($pendaftar->status_verifikasi == 'Terverifikasi')
                        <span class="badge badge-success badge-lg">
                            <i class="fas fa-check"></i> Terverifikasi
                        </span>
                        @elseif ($pendaftar->status_verifikasi == 'Ditolak')
                        <span class="badge badge-danger badge-lg">
                            <i class="fas fa-times"></i> Ditolak
                        </span>
                        @else
                        <span class="badge badge-warning badge-lg">
                            <i class="fas fa-clock"></i> Belum Diverifikasi
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label>Status Seleksi</label>
                    <div>
                        @if ($pendaftar->status_seleksi == 'Diterima')
                        <span class="badge badge-success badge-lg">
                            <i class="fas fa-thumbs-up"></i> Diterima
                        </span>
                        @elseif ($pendaftar->status_seleksi == 'Tidak Diterima')
                        <span class="badge badge-danger badge-lg">
                            <i class="fas fa-thumbs-down"></i> Tidak Diterima
                        </span>
                        @else
                        <span class="badge badge-secondary badge-lg">
                            <i class="fas fa-hourglass-half"></i> Belum Diumumkan
                        </span>
                        @endif
                    </div>
                </div>

                <!-- @if($pendaftar->catatan_admin)
                <div class="form-group">
                    <label>Catatan Admin</label>
                    <div class="alert alert-info">
                        {{ $pendaftar->catatan_admin }}
                    </div>
                </div>
                @endif -->
            </div>
        </div>

        <!-- Form Verifikasi -->
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit"></i> Verifikasi Pendaftaran
                </h3>
            </div>
            <form action="{{ route('admin.pendaftar.verifikasi', $pendaftar->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Status Verifikasi <span class="text-danger">*</span></label>
                        <select name="status_verifikasi" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Terverifikasi" {{ $pendaftar->status_verifikasi == 'Terverifikasi' ? 'selected' : '' }}>
                                Terverifikasi
                            </option>
                            <option value="Ditolak" {{ $pendaftar->status_verifikasi == 'Ditolak' ? 'selected' : '' }}>
                                Ditolak
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status Seleksi</label>
                        <select name="status_seleksi" class="form-control">
                            <option value="">-- Pilih Status --</option>
                            <option value="Diterima" {{ $pendaftar->status_seleksi == 'Diterima' ? 'selected' : '' }}>
                                Diterima
                            </option>
                            <option value="Tidak Diterima" {{ $pendaftar->status_seleksi == 'Tidak Diterima' ? 'selected' : '' }}>
                                Tidak Diterima
                            </option>
                            <option value="Belum Diumumkan" {{ $pendaftar->status_seleksi == 'Belum Diumumkan' ? 'selected' : '' }}>
                                Belum Diumumkan
                            </option>
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control" rows="4"
                            placeholder="Berikan catatan untuk pendaftar...">{{ $pendaftar->catatan_admin }}</textarea>
                    </div> -->
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-save"></i> Simpan Verifikasi
                    </button>
                </div>
            </form>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <!-- <button type="button" class="btn btn-danger btn-block"
                        onclick="confirmDelete('{{ $pendaftar->id }}', '{{ $pendaftar->nama }}')">
                        <i class="fas fa-trash"></i> Hapus Data
                    </button> -->
                    <a href="{{ route('admin.pendaftar.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .badge-lg {
        font-size: 1em;
        padding: 0.5rem 1rem;
    }

    .card {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .table-borderless td {
        border: none;
        padding: 0.5rem 0;
    }

    .border {
        border: 1px solid #dee2e6 !important;
    }

    .alert {
        margin-bottom: 0;
    }
</style>
@stop

@section('js')
<script>
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Data?',
            text: `Yakin ingin menghapus data ${nama}? Data yang dihapus tidak dapat dikembalikan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/pendaftar/${id}`;
                form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('
        success ') }}',
        timer: 3000,
        showConfirmButton: false
    });
    @endif
</script>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush