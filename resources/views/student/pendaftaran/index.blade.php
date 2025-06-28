@extends('adminlte::page')

@section('title', 'Lengkapi Data Pendaftaran')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-edit text-primary"></i> Lengkapi Data Pendaftaran</h1>
    <div class="progress" style="width: 200px; height: 25px;">
        <div
            class="progress-bar bg-success"
            role="progressbar"
            id="progressBar"
            aria-valuenow="{{ $progress }}"
            aria-valuemin="0"
            aria-valuemax="100"
            style="width: {{ $progress }}%;">
            {{ $progress }}%
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        @endif

        @if($pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi')
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Informasi:</strong> Data pendaftaran Anda sudah diverifikasi dan tidak dapat diubah.
            <a href="{{ route('student.status.index') }}" class="btn btn-sm btn-primary ml-2">
                <i class="fas fa-eye"></i> Lihat Status
            </a>
        </div>
        @elseif($pendaftaran && $pendaftaran->status_verifikasi === 'Ditolak')
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Perhatian:</strong> Data pendaftaran Anda ditolak. Silakan perbaiki dan kirim ulang.
        </div>
        @endif

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-edit"></i> Form Data Pendaftaran</h3>
                @if($pendaftaran)
                <div class="card-tools">
                    <span class="badge badge-{{ $pendaftaran->status_verifikasi_badge }}">
                        {{ $pendaftaran->status_verifikasi }}
                    </span>
                </div>
                @endif
            </div>

            <form action="{{ route('student.pendaftaran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">

                    {{-- Data Pribadi --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama"><i class="fas fa-user"></i> Nama Lengkap *</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" value="{{ old('nama', $pendaftaran->nama ?? '') }}"
                                    {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>
                                @error('nama')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nisn"><i class="fas fa-id-card"></i> NISN *</label>
                                <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                                    id="nisn" name="nisn" value="{{ old('nisn', $pendaftaran->nisn ?? '') }}"
                                    maxlength="10" {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>
                                @error('nisn')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nik"><i class="fas fa-address-card"></i> NIK *</label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                    id="nik" name="nik" value="{{ old('nik', $pendaftaran->nik ?? '') }}"
                                    maxlength="16" {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>
                                @error('nik')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempat_lahir"><i class="fas fa-map-marker-alt"></i> Tempat Lahir *</label>
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                    id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir ?? '') }}"
                                    placeholder="Jakarta" {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>
                                @error('tempat_lahir')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl_lahir"><i class="fas fa-calendar"></i> Tanggal Lahir *</label>
                                <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror"
                                    id="tgl_lahir" name="tgl_lahir"
                                    value="{{ old('tgl_lahir', $pendaftaran && $pendaftaran->tgl_lahir ? $pendaftaran->tgl_lahir->format('Y-m-d') : '') }}"
                                    {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>
                                @error('tgl_lahir')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sekolah_asal"><i class="fas fa-school"></i> Sekolah Asal *</label>
                                <input type="text" class="form-control @error('sekolah_asal') is-invalid @enderror"
                                    id="sekolah_asal" name="sekolah_asal"
                                    value="{{ old('sekolah_asal', $pendaftaran->sekolah_asal ?? '') }}"
                                    {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>
                                @error('sekolah_asal')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="alamat"><i class="fas fa-home"></i> Alamat Lengkap *</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror"
                                    id="alamat" name="alamat" rows="3"
                                    {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>{{ old('alamat', $pendaftaran->alamat ?? '') }}</textarea>
                                @error('alamat')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="jenis_kelamin"><i class="fas fa-venus-mars"></i> Jenis Kelamin *</label>
                                <select class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                    id="jenis_kelamin" name="jenis_kelamin"
                                    {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'disabled' : '' }} required>
                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin ?? '') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin ?? '') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Preview TTL --}}
                    @if($pendaftaran && $pendaftaran->tempat_lahir && $pendaftaran->tgl_lahir)
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>TTL Lengkap:</strong> {{ $pendaftaran->ttl_lengkap }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="agama"><i class="fas fa-praying-hands"></i> Agama *</label>
                                <input type="text" class="form-control @error('agama') is-invalid @enderror"
                                    id="agama" name="agama"
                                    value="{{ old('agama', $pendaftaran->agama ?? '') }}"
                                    {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>
                                @error('agama')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_hp"><i class="fas fa-phone"></i> No. HP *</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                    id="no_hp" name="no_hp"
                                    value="{{ old('no_hp', $pendaftaran->no_hp ?? '') }}"
                                    {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>
                                @error('no_hp')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_ayah"><i class="fas fa-male"></i> Nama Ayah *</label>
                                <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror"
                                    id="nama_ayah" name="nama_ayah"
                                    value="{{ old('nama_ayah', $pendaftaran->nama_ayah ?? '') }}"
                                    {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>
                                @error('nama_ayah')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pekerjaan_ayah"><i class="fas fa-briefcase"></i> Pekerjaan Ayah *</label>
                                <input type="text" class="form-control @error('pekerjaan_ayah') is-invalid @enderror"
                                    id="pekerjaan_ayah" name="pekerjaan_ayah"
                                    value="{{ old('pekerjaan_ayah', $pendaftaran->pekerjaan_ayah ?? '') }}"
                                    {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>
                                @error('pekerjaan_ayah')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_ibu"><i class="fas fa-female"></i> Nama Ibu *</label>
                                <input type="text" class="form-control @error('nama_ibu') is-invalid @enderror"
                                    id="nama_ibu" name="nama_ibu"
                                    value="{{ old('nama_ibu', $pendaftaran->nama_ibu ?? '') }}"
                                    {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>
                                @error('nama_ibu')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pekerjaan_ibu"><i class="fas fa-briefcase"></i> Pekerjaan Ibu *</label>
                                <input type="text" class="form-control @error('pekerjaan_ibu') is-invalid @enderror"
                                    id="pekerjaan_ibu" name="pekerjaan_ibu"
                                    value="{{ old('pekerjaan_ibu', $pendaftaran->pekerjaan_ibu ?? '') }}"
                                    {{ $pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi' ? 'readonly' : '' }} required>
                                @error('pekerjaan_ibu')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5><i class="fas fa-file-upload text-info"></i> Upload Dokumen</h5>
                    <p class="text-muted">Format: PDF, Maksimal 2MB per file</p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kk_file"><i class="fas fa-file-pdf"></i> Kartu Keluarga</label>
                                @if(!($pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi'))
                                <input type="file" class="form-control-file @error('kk_file') is-invalid @enderror"
                                    id="kk_file" name="kk_file" accept=".pdf">
                                @endif
                                @if($berkasPendaftaran && $berkasPendaftaran->kk_file)
                                <div class="mt-2">
                                    <small class="text-success">
                                        <i class="fas fa-check"></i> File sudah diupload
                                        <a href="{{ Storage::url($berkasPendaftaran->kk_file) }}" target="_blank" class="ml-2">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </small>
                                </div>
                                @endif
                                @error('kk_file')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="akta_file"><i class="fas fa-file-pdf"></i> Akta Kelahiran</label>
                                @if(!($pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi'))
                                <input type="file" class="form-control-file @error('akta_file') is-invalid @enderror"
                                    id="akta_file" name="akta_file" accept=".pdf">
                                @endif
                                @if($berkasPendaftaran && $berkasPendaftaran->akta_file)
                                <div class="mt-2">
                                    <small class="text-success">
                                        <i class="fas fa-check"></i> File sudah diupload
                                        <a href="{{ Storage::url($berkasPendaftaran->akta_file) }}" target="_blank" class="ml-2">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </small>
                                </div>
                                @endif
                                @error('akta_file')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto"><i class="fas fa-image"></i> Foto Terbaru</label>
                                @if(!($pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi'))
                                <input type="file" class="form-control-file @error('foto') is-invalid @enderror"
                                    id="foto" name="foto" accept=".jpg,.jpeg,.png">
                                @endif

                                @if($berkasPendaftaran && $berkasPendaftaran->foto)
                                <div class="mt-2">
                                    <small class="text-success">
                                        <i class="fas fa-check"></i> Foto sudah diupload
                                        <a href="{{ Storage::url($berkasPendaftaran->foto) }}" target="_blank" class="ml-2">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </small>
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($berkasPendaftaran->foto) }}" alt="Pas Foto" width="120" class="img-thumbnail">
                                    </div>
                                </div>
                                @endif

                                @error('foto')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="piagam_file"><i class="fas fa-file-pdf"></i> Piagam/Sertifikat (Opsional)</label>
                                @if(!($pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi'))
                                <input type="file" class="form-control-file @error('piagam_file') is-invalid @enderror"
                                    id="piagam_file" name="piagam_file" accept=".pdf">
                                @endif
                                @if($berkasPendaftaran && $berkasPendaftaran->piagam_file)
                                <div class="mt-2">
                                    <small class="text-success">
                                        <i class="fas fa-check"></i> File sudah diupload
                                        <a href="{{ Storage::url($berkasPendaftaran->piagam_file) }}" target="_blank" class="ml-2">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </small>
                                </div>
                                @endif
                                @error('piagam_file')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    @if(!($pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi'))
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ $pendaftaran ? 'Update Data' : 'Simpan Data' }}
                    </button>
                    @endif
                    <a href="{{ route('student.status.index') }}" class="btn btn-info {{ !($pendaftaran && $pendaftaran->status_verifikasi === 'Terverifikasi') ? 'ml-2' : '' }}">
                        <i class="fas fa-chart-line"></i> Lihat Status
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .progress {
        border-radius: 10px;
    }

    .form-control-file {
        padding: 0.375rem 0;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Auto format NIK dan NISN
        $('#nik').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 16) {
                this.value = this.value.slice(0, 16);
            }
        });

        $('#nisn').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    });
</script>
@stop