@extends('layouts.app')

@section('subtitle', 'Kelola Data Pendaftar')
@section('content_header_title', 'Kelola Data Pendaftar')

@section('content_body')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Pendaftar</h3>
        <div class="card-tools">
            <form method="GET" class="form-inline">
                <div class="input-group input-group-sm mr-2">
                    <input type="text" class="form-control" placeholder="Cari Nama...">
                </div>
                <div class="input-group input-group-sm mr-2">
                    <input type="date" class="form-control">
                </div>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-search"></i> Filter
                </button>
            </form>
        </div>
    </div>

    <div class="card-body table-responsive p-0" style="max-height: 500px;">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NISN</th>
                    <th>JK</th>
                    <th>Tanggal Lahir</th>
                    <th>No. HP</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['nisn'] }}</td>
                    <td>{{ $item['jenis_kelamin'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['tanggal_lahir'])->format('d-m-Y') }}</td>
                    <td>{{ $item['no_telpon'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('d-m-Y') }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <button onclick="confirm('Yakin ingin hapus?')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <ul class="pagination pagination-sm m-0 float-right">
            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
        </ul>
    </div>
</div>

@stop