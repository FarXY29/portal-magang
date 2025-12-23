<!DOCTYPE html>
<html>
<head>
    <title>Laporan Statistik & Demografi</title>
    <style>
        body { font-family: "Times New Roman", serif; font-size: 12pt; }
        .kop { text-align: center; margin-bottom: 20px; border-bottom: 3px double black; padding-bottom: 10px; }
        .kop h1, .kop h2 { margin: 0; text-transform: uppercase; }
        
        .section-title { font-weight: bold; margin-top: 30px; margin-bottom: 10px; text-decoration: underline; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        td { text-align: left; } /* Default left, override di html untuk center */
        
        .ttd { margin-top: 50px; float: right; text-align: center; width: 40%; }
    </style>
</head>
<body>
    <div class="kop">
        <h2>Pemerintah Kota Banjarmasin</h2>
        <h3>{{ Auth::user()->skpd->nama_dinas ?? 'Dinas Terkait' }}</h3>
        <p>Laporan Analisis Statistik & Demografi Magang</p>
    </div>

    <div class="section-title">A. STATISTIK PEMINAT & RASIO KEKETATAN</div>
    <table>
        <thead>
            <tr>
                <th width="35%">Posisi Magang</th>
                <th width="15%">Kuota</th>
                <th width="15%">Pelamar</th>
                <th width="15%">Diterima</th>
                <th width="20%">Rasio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats as $st)
            <tr>
                <td>{{ $st->judul_posisi }}</td>
                <td style="text-align: center;">{{ $st->kuota + $st->diterima }}</td>
                <td style="text-align: center;">{{ $st->total_pelamar }}</td>
                <td style="text-align: center;">{{ $st->diterima }}</td>
                <td style="text-align: center;">
                    1 : {{ $st->total_pelamar > 0 ? round($st->total_pelamar / max(($st->kuota + $st->diterima), 1), 1) : 0 }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">B. DEMOGRAFI ASAL INSTANSI (Peserta Diterima)</div>
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="50%">Nama Instansi / Sekolah / Universitas</th>
                <th width="20%">Jumlah Peserta</th>
                <th width="20%">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = $demografi->sum('total'); @endphp
            @forelse($demografi as $idx => $demo)
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td>{{ $demo->asal_instansi }}</td>
                <td style="text-align: center;">{{ $demo->total }}</td>
                <td style="text-align: center;">
                    {{ $grandTotal > 0 ? round(($demo->total / $grandTotal) * 100, 1) : 0 }}%
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align: center;">Tidak ada data.</td></tr>
            @endforelse
            @if($grandTotal > 0)
            <tr style="font-weight: bold; background-color: #eee;">
                <td colspan="2" style="text-align: right;">TOTAL</td>
                <td style="text-align: center;">{{ $grandTotal }}</td>
                <td style="text-align: center;">100%</td>
            </tr>
            @endif
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