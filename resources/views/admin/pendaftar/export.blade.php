<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Data Pendaftar PPDB</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            font-size: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            color: #007bff;
            font-size: 16px;
        }

        .header h2 {
            margin: 5px 0;
            color: #333;
            font-size: 14px;
        }

        .header h3 {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }

        .info-section {
            margin-bottom: 15px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }

        .info-section h4 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .stats-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .stats-table td {
            padding: 5px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .stats-table .header-cell {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .filter-info {
            margin-bottom: 15px;
            font-size: 9px;
            color: #666;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            font-size: 8px;
        }

        .data-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            color: white;
            font-size: 7px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 9px;
        }

        .signature {
            margin-top: 30px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            margin-left: 50px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 150px;
            margin: 40px auto 5px auto;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN DATA PENDAFTAR</h1>
        <h2>PENERIMAAN PESERTA DIDIK BARU (PPDB)</h2>
        <h3>SMP AL-IRSYAD</h3>
        <h3>TAHUN PELAJARAN {{ date('Y') }}/{{ date('Y') + 1 }}</h3>
    </div>

    <!-- Info Export -->
    <div class="info-section">
        <h4>INFORMASI LAPORAN</h4>
        <table style="width: 100%; font-size: 9px;">
            <tr>
                <td width="20%">Tanggal Export</td>
                <td width="2%">:</td>
                <td width="28%">{{ $tanggal_export }}</td>
                <td width="20%">Diekspor oleh</td>
                <td width="2%">:</td>
                <td width="28%">{{ $admin }}</td>
            </tr>
            <tr>
                <td>Total Data</td>
                <td>:</td>
                <td>{{ $stats['total'] }} pendaftar</td>
                <td>Filter Aktif</td>
                <td>:</td>
                <td>
                    @if($filter['nama'] || $filter['status_verifikasi'] || $filter['tanggal_dari'] || $filter['tanggal_sampai'])
                    Ya
                    @else
                    Tidak
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Filter Info -->
    @if($filter['nama'] || $filter['status_verifikasi'] || $filter['tanggal_dari'] || $filter['tanggal_sampai'])
    <div class="filter-info">
        <strong>Filter yang Diterapkan:</strong>
        @if($filter['nama'])
        Nama: "{{ $filter['nama'] }}" |
        @endif
        @if($filter['status_verifikasi'])
        Status: {{ $filter['status_verifikasi'] }} |
        @endif
        @if($filter['tanggal_dari'])
        Dari: {{ \Carbon\Carbon::parse($filter['tanggal_dari'])->format('d/m/Y') }} |
        @endif
        @if($filter['tanggal_sampai'])
        Sampai: {{ \Carbon\Carbon::parse($filter['tanggal_sampai'])->format('d/m/Y') }}
        @endif
    </div>
    @endif

    <!-- Statistik -->
    <div class="info-section">
        <h4>STATISTIK DATA</h4>
        <table class="stats-table">
            <tr>
                <td class="header-cell">Total Pendaftar</td>
                <td class="header-cell">Terverifikasi</td>
                <td class="header-cell">Belum Verifikasi</td>
                <td class="header-cell">Ditolak</td>
                <td class="header-cell">Diterima</td>
                <td class="header-cell">Tidak Diterima</td>
            </tr>
            <tr>
                <td><strong>{{ $stats['total'] }}</strong></td>
                <td><strong>{{ $stats['terverifikasi'] }}</strong></td>
                <td><strong>{{ $stats['belum_verifikasi'] }}</strong></td>
                <td><strong>{{ $stats['ditolak'] }}</strong></td>
                <td><strong>{{ $stats['diterima'] }}</strong></td>
                <td><strong>{{ $stats['tidak_diterima'] }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="15%">Nama Lengkap</th>
                <th width="8%">NISN</th>
                <th width="8%">NIK</th>
                <th width="6%">L/P</th>
                <th width="15%">Sekolah Asal</th>
                <th width="8%">No. HP</th>
                <th width="10%">Email</th>
                <th width="8%">Tgl Daftar</th>
                <th width="8%">Status Verifikasi</th>
                <th width="8%">Hasil Seleksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendaftars as $index => $pendaftar)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $pendaftar->nama }}</td>
                <td>{{ $pendaftar->nisn ?? '-' }}</td>
                <td>{{ $pendaftar->nik ?? '-' }}</td>
                <td style="text-align: center;">{{ $pendaftar->jenis_kelamin ? substr($pendaftar->jenis_kelamin, 0, 1) : '-' }}</td>
                <td>{{ $pendaftar->sekolah_asal ?? '-' }}</td>
                <td>{{ $pendaftar->no_hp ?? '-' }}</td>
                <td>{{ $pendaftar->user->email }}</td>
                <td style="text-align: center;">{{ $pendaftar->created_at->format('d/m/Y') }}</td>
                <td style="text-align: center;">
                    <span class="status-badge badge-{{ $pendaftar->status_verifikasi_badge }}">
                        {{ $pendaftar->status_verifikasi }}
                    </span>
                </td>
                <td style="text-align: center;">
                    @if(isset($pengumumans[$pendaftar->user_id]))
                    <span class="status-badge badge-{{ $pengumumans[$pendaftar->user_id]->hasil_seleksi == 'Diterima' ? 'success' : 'danger' }}">
                        {{ $pengumumans[$pendaftar->user_id]->hasil_seleksi }}
                    </span>
                    @else
                    <span class="status-badge badge-info">Belum Diumumkan</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align: center; padding: 20px;">
                    Tidak ada data pendaftar
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh Sistem PPDB SMP Al-Irsyad</p>
        <p>Dicetak pada: {{ $tanggal_export }} | Total: {{ $stats['total'] }} data</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature">
        <div class="signature-box">
            <p>Admin PPDB</p>
            <!-- <img src="{{ asset('images/ttd.png') }}" class="w-16 h-16" alt="TTD"> -->
            <div class="signature-line"></div>
            <p><strong>{{ $admin }}</strong></p>
            <p>SMP Al-Irsyad</p>
        </div>
    </div>
</body>

</html>