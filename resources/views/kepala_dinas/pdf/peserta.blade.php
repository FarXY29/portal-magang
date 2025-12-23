<!DOCTYPE html>
<html>
<head>
    <title>Laporan Eksekutif Peserta</title>
    <style>
        body { font-family: "Times New Roman", serif; font-size: 11pt; }
        .kop { text-align: center; margin-bottom: 20px; border-bottom: 3px double black; padding-bottom: 10px; }
        .kop h1 { font-size: 16pt; margin: 0; text-transform: uppercase; }
        .kop h2 { font-size: 14pt; margin: 0; text-transform: uppercase; }
        .kop p { margin: 0; font-style: italic; font-size: 10pt; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 6px; vertical-align: top; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        
        .ttd { margin-top: 50px; float: right; text-align: center; width: 40%; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="kop">
        <h1>Pemerintah Kota Banjarmasin</h1>
        <h2>{{ Auth::user()->skpd->nama_dinas ?? 'Dinas Terkait' }}</h2>
        <p>Laporan Data Eksekutif Peserta Magang</p>
    </div>

    <h3 style="text-align: center; text-decoration: underline;">REKAPITULASI PESERTA & PENILAIAN</h3>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama / NIK</th>
                <th width="20%">Asal Instansi</th>
                <th width="20%">Posisi & Pembimbing</th>
                <th width="20%">Periode</th>
                <th width="15%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($interns as $index => $row)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $row->user->name }}</strong><br>
                    <small>NIK: {{ $row->user->nik }}</small>
                </td>
                <td>{{ $row->user->asal_instansi }}</td>
                <td>
                    {{ $row->position->judul_posisi }}<br>
                    <small>Mentor: {{ $row->mentor->name ?? '-' }}</small>
                </td>
                <td style="font-size: 10pt;">
                    {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d/m/Y') }} s.d.<br>
                    {{ $row->tanggal_selesai ? \Carbon\Carbon::parse($row->tanggal_selesai)->format('d/m/Y') : 'Sekarang' }}
                </td>
                <td style="text-align: center;">
                    @if($row->nilai_angka)
                        <strong>{{ $row->nilai_angka }}</strong> <br>
                        ({{ $row->predikat }})
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data peserta.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd">
        <p>Banjarmasin, {{ date('d F Y') }}</p>
        <p>Kepala Dinas,</p>
        <br><br><br>
        <p style="font-weight: bold; text-decoration: underline;">{{ Auth::user()->name }}</p>
        <p>NIP. {{ Auth::user()->nik ?? '-' }}</p>
    </div>
</body>
</html>