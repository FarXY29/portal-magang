<!DOCTYPE html>
<html>
<head>
    <title>Formulir Penilaian PKL</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        /* Layout untuk Header Info */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 2px;
            vertical-align: top;
            border: none;
        }
        .label-col { width: 35%; }
        .sep-col { width: 2%; }
        
        /* Layout Tabel Nilai */
        .grade-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .grade-table th, .grade-table td {
            border: 1px solid black;
            padding: 5px 10px;
        }
        .grade-table th {
            text-align: center;
            background-color: #f0f0f0; /* Opsional: abu-abu tipis di header */
        }
        .col-no { width: 8%; text-align: center; }
        .col-act { width: 60%; }
        .col-val { width: 32%; text-align: center; font-weight: bold; }

        /* Layout Tanda Tangan */
        .signature-table {
            width: 100%;
            margin-top: 50px;
            border: none;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            border: none;
        }
        .sign-space {
            height: 80px; /* Ruang untuk tanda tangan basah/cap */
        }
    </style>
</head>
<body>

    <div class="text-center text-bold" style="margin-bottom: 30px;">
        <span style="font-size: 14pt;">Formulir Penilaian Praktek Kerja Lapang (PKL)</span>
    </div>

    <table class="info-table">
        <tr>
            <td class="label-col">Nama Pembimbing Lapangan</td>
            <td class="sep-col">:</td>
            <td>{{ $app->mentor->name ?? '.........................' }}</td>
        </tr>
        <tr>
            <td class="label-col">Instansi Kerja Praktek</td>
            <td class="sep-col">:</td>
            <td>
                {{ $app->position->skpd->nama_dinas }}<br>
                <span style="font-size: 10pt; font-style: italic;">{{ $app->position->skpd->alamat ?? 'Banjarmasin' }}</span>
            </td>
        </tr>
    </table>

    <p>menyatakan bahwa peserta Praktek Kerja Lapangan berikut ini:</p>

    <table class="info-table">
        <tr>
            <td class="label-col">Nama Mahasiswa</td>
            <td class="sep-col">:</td>
            <td class="text-bold">{{ $app->user->name }}</td>
        </tr>
        <tr>
            <td class="label-col">Nomor Pokok Mahasiswa (NPM)</td>
            <td class="sep-col">:</td>
            <td>{{ $app->user->nim ?? '.........................' }}</td> 
        </tr>
        <tr>
            <td class="label-col">Waktu Pelaksanaan</td>
            <td class="sep-col">:</td>
            <td>
                {{ \Carbon\Carbon::parse($app->start_date)->translatedFormat('d F Y') }} – 
                {{ \Carbon\Carbon::parse($app->end_date)->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>

    <div style="text-align: justify; margin-bottom: 15px;">
        Telah menyelesaikan Praktek Kerja Lapangan di Dinas kami. Dengan mempertimbangkan segala aspek, baik dari segi bobot pekerjaan maupun pelaksanaan Kerja Praktek, maka kami memutuskan bahwa yang bersangkutan telah menyelesaikan kewajibannya dengan hasil sebagai berikut:
    </div>

    <table class="grade-table">
        <thead>
            <tr>
                <th class="col-no">No.</th>
                <th class="col-act">Aktivitas Yang Dinilai</th>
                <th class="col-val">Nilai (Berbentuk Angka)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $kriteria = [
                    'Sikap/Sopan Santun' => $app->nilai_sikap,
                    'Kedisiplinan' => $app->nilai_disiplin,
                    'Kesungguhan' => $app->nilai_kesungguhan,
                    'Kemampuan Bekerja Mandiri' => $app->nilai_mandiri,
                    'Kemampuan Bekerja Sama' => $app->nilai_kerjasama,
                    'Ketelitian' => $app->nilai_ketelitian,
                    'Kemampuan Mengemukakan Pendapat' => $app->nilai_pendapat,
                    'Kemampuan Menyerap Hal Baru' => $app->nilai_serap_hal_baru,
                    'Inisiatif dan Kreatifitas' => $app->nilai_inisiatif,
                    'Kepuasan Pemberi Kerja Praktek' => $app->nilai_kepuasan,
                ];
                $no = 1;
            @endphp

            @foreach($kriteria as $label => $nilai)
            <tr>
                <td class="col-no">{{ $no++ }}</td>
                <td>{{ $label }}</td>
                <td class="col-val">{{ $nilai }}</td>
            </tr>
            @endforeach
            
            <tr style="background-color: #fafafa;">
                <td colspan="2" style="text-align: right; font-weight: bold; padding-right: 15px;">Rata - Rata Akhir</td>
                <td class="col-val">{{ $app->nilai_rata_rata }}</td>
            </tr>
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td width="50%">
                Mengetahui,<br>
                <span style="font-weight: bold;">{{ $app->position->skpd->jabatan_pejabat ?? 'Kepala Dinas' }}</span><br>
                {{ $app->position->skpd->nama_dinas }}
                <div class="sign-space">
                    </div>
                <span class="text-bold text-underline">
                    {{ $app->position->skpd->nama_pejabat ?? '........................................' }}
                </span><br>
                NIP. {{ $app->position->skpd->nip_pejabat ?? '....................' }}
            </td>
            <td width="50%">
                Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Pembimbing Lapangan
                <div class="sign-space">
                    </div>
                <span class="text-bold text-underline">{{ $app->mentor->name }}</span><br>
                NIP. ......................... </td>
        </tr>
    </table>

</body>
</html>