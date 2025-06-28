<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Pengumuman PPDB - {{ $pendaftaran->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #007bff;
            font-size: 18px;
        }

        .header h2 {
            margin: 5px 0;
            color: #333;
            font-size: 16px;
        }

        .header h3 {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }

        .content {
            margin: 20px 0;
        }

        .data-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .data-table td {
            padding: 5px;
            vertical-align: top;
        }

        .data-table .label {
            width: 30%;
            font-weight: bold;
        }

        .hasil-box {
            text-align: center;
            padding: 20px;
            margin: 20px 0;
            border: 2px solid;
            border-radius: 10px;
        }

        .diterima {
            background-color: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .ditolak {
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        .hasil-box h2 {
            margin: 0;
            font-size: 24px;
        }

        .info-box {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }

        .info-box h4 {
            margin-top: 0;
            color: #0c5460;
        }

        .info-box ul {
            margin-bottom: 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            margin-left: 50px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 200px;
            margin: 50px auto 10px auto;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>PENGUMUMAN HASIL SELEKSI</h1>
        <h2>PENERIMAAN PESERTA DIDIK BARU (PPDB)</h2>
        <h3>SMP AL-IRSYAD</h3>
        <h3>TAHUN PELAJARAN {{ date('Y') }}/{{ date('Y') + 1 }}</h3>
    </div>

    <!-- Data Peserta -->
    <div class="content">
        <h3>DATA PESERTA</h3>
        <table class="data-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td>: {{ $pendaftaran->nama }}</td>
                <td class="label">Jenis Kelamin</td>
                <td>: {{ $pendaftaran->jenis_kelamin ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td>: {{ $pendaftaran->nisn ?? '-' }}</td>
                <td class="label">Sekolah Asal</td>
                <td>: {{ $pendaftaran->sekolah_asal ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td>: {{ $pendaftaran->nik ?? '-' }}</td>
                <td class="label">No. HP</td>
                <td>: {{ $pendaftaran->no_hp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tgl Lahir</td>
                <td>: {{ $pendaftaran->tempat_lahir && $pendaftaran->tgl_lahir ? $pendaftaran->tempat_lahir . ', ' . \Carbon\Carbon::parse($pendaftaran->tgl_lahir)->format('d F Y') : '-' }}</td>
                <td class="label">Tanggal Daftar</td>
                <td>: {{ $pendaftaran->created_at->format('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <!-- Hasil Seleksi -->
    <div class="hasil-box {{ $pengumuman->hasil_seleksi == 'Diterima' ? 'diterima' : 'ditolak' }}">
        <h2>{{ strtoupper($pengumuman->hasil_seleksi) }}</h2>
        @if($pengumuman->hasil_seleksi == 'Diterima')
        <p><strong>Selamat! Anda diterima sebagai peserta didik baru di SMP Al-Irsyad</strong></p>
        @else
        <p><strong>Mohon maaf, Anda belum dapat diterima pada periode ini</strong></p>
        @endif
    </div>

    @if($pengumuman->hasil_seleksi == 'Diterima')
    <!-- Informasi Selanjutnya -->
    <div class="info-box">
        <h4>INFORMASI SELANJUTNYA</h4>
        <ul>
            <li>Silakan melakukan daftar ulang pada tanggal yang telah ditentukan</li>
            <li>Bawa dokumen asli dan fotokopi yang diperlukan</li>
            <li>Hubungi panitia PPDB untuk informasi lebih lanjut</li>
            <li>Simpan pengumuman ini sebagai bukti penerimaan</li>
        </ul>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Tanggal Pengumuman: {{ \Carbon\Carbon::parse($pengumuman->tanggal)->format('d F Y') }}</p>
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature">
        <div class="signature-box">
            <p>Panitia PPDB</p>
            <div class="signature-line"></div>
            <p><strong>SMP Al-Irsyad</strong></p>
        </div>
    </div>
</body>

</html>