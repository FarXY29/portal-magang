<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kegiatan Magang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; text-transform: uppercase; }
        .header h1 { margin: 0; font-size: 16px; }
        .header h2 { margin: 5px 0; font-size: 14px; }
        
        .meta { margin-bottom: 20px; width: 100%; }
        .meta td { padding: 3px; vertical-align: top; }
        
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data th, table.data td { border: 1px solid black; padding: 5px; text-align: left; }
        table.data th { background-color: #f0f0f0; text-align: center; }
        
        .status-ok { color: green; font-weight: bold; }
        .status-rev { color: red; font-style: italic; }
        
        .footer { width: 100%; margin-top: 30px; }
        .ttd { width: 30%; float: right; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Kegiatan Harian (Logbook)</h1>
        <h2>{{ $app->position->skpd->nama_dinas }}</h2>
        <p>Pemerintah Kota Banjarmasin</p>
    </div>

    <table class="meta">
        <tr>
            <td width="15%">Nama Peserta</td>
            <td width="2%">:</td>
            <td>{{ $user->name }}</td>
            <td width="15%">Posisi</td>
            <td width="2%">:</td>
            <td>{{ $app->position->judul_posisi }}</td>
        </tr>
        <tr>
            <td>NIM/NISN</td>
            <td>:</td>
            <td>{{ $user->nik ?? '-' }}</td> <!-- Asumsi NIK dipakai sbg NIM sementara -->
            <td>Asal Instansi</td>
            <td>:</td>
            <td>{{ $user->asal_instansi }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="50%">Uraian Kegiatan</th>
                <th width="15%">Validasi</th>
                <th width="15%">Paraf Mentor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $index => $log)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($log->tanggal)->format('d-m-Y') }}</td>
                <td>
                    {{ $log->kegiatan }}
                    @if($log->komentar_mentor)
                        <br><i style="font-size: 10px; color: gray;">Catatan: {{ $log->komentar_mentor }}</i>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($log->status_validasi == 'disetujui')
                        <span class="status-ok">Disetujui</span>
                    @elseif($log->status_validasi == 'revisi')
                        <span class="status-rev">Revisi</span>
                    @else
                        Pending
                    @endif
                </td>
                <td></td> <!-- Kolom kosong untuk paraf manual jika diprint -->
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd">
            <p>Banjarmasin, {{ date('d F Y') }}</p>
            <p>Pembimbing Lapangan,</p>
            <br><br><br>
            <p><strong>{{ $app->mentor->name ?? '.........................' }}</strong></p>
            <p>NIP. {{ $app->mentor->nik ?? '................' }}</p>
        </div>
    </div>

</body>
</html>