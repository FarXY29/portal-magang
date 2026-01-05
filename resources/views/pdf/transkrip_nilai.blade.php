@php
    // Atur bahasa tanggal ke Indonesia
    \Carbon\Carbon::setLocale('id');
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>Formulir Penilaian PKL</title>
    <style>
        /* PENGATURAN HALAMAN AGAR MUAT 1 LEMBAR */
        @page {
            /* Margin: Atas 1.5cm, Kanan 2cm, Bawah 1.5cm, Kiri 2.5cm */
            margin: 1.5cm 2cm 1cm 2.5cm; 
            size: A4 portrait;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt; /* Diubah dari 12pt agar lebih muat */
            line-height: 1.2; /* Spasi baris dirapatkan sedikit */
            margin: 0;
            padding: 0;
        }
        
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-justify { text-align: justify; }
        
        /* Layout Tabel Informasi (Header) */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .info-table td {
            vertical-align: top;
            padding: 1px 0; /* Padding vertikal diperkecil */
        }
        .label-col { width: 35%; }
        .sep-col { width: 2%; text-align: center; }
        
        /* Layout Tabel Nilai */
        .grade-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px; /* Jarak bawah diperkecil */
        }
        .grade-table th, .grade-table td {
            border: 1px solid black;
            padding: 4px 6px; /* Cell lebih rapat */
        }
        .grade-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-size: 11pt;
        }
        .col-no { width: 5%; text-align: center; }
        .col-nilai { width: 25%; text-align: center; font-weight: bold; }

        /* Layout Tanda Tangan */
        .signature-table {
            width: 100%;
            margin-top: 20px; /* Jarak ke atas diperkecil */
            border: none;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
        }
        .sign-space {
            height: 60px; /* Ruang tanda tangan dikecilkan sedikit (dari 70-80px) */
        }
    </style>
</head>
<body>

    <div class="text-center text-bold" style="margin-bottom: 20px;">
        <span style="font-size: 13pt; text-transform: uppercase;">Formulir Penilaian Praktek Kerja Lapang (PKL)</span>
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
                Banjarmasin
            </td>
        </tr>
    </table>

    <p style="margin: 8px 0;">menyatakan bahwa peserta Praktek Kerja Lapangan berikut ini:</p>

    <table class="info-table">
        <tr>
            <td class="label-col">Nama Mahasiswa</td>
            <td class="sep-col">:</td>
            <td class="text-bold">{{ $app->user->name }}</td>
        </tr>
        <tr>
            <td class="label-col">Nomor Pokok Mahasiswa (NPM)</td>
            <td class="sep-col">:</td>
            <td>{{ $app->user->nim ?? '2210010154' }}</td> 
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

    <div class="text-justify" style="margin-top: 8px; margin-bottom: 8px;">
        Telah menyelesaikan Praktek Kerja Lapangan di Dinas kami. Dengan mempertimbangkan segala aspek, baik dari segi bobot pekerjaan maupun pelaksanaan Kerja Praktek, maka kami memutuskan bahwa yang bersangkutan telah menyelesaikan kewajibannya dengan hasil sebagai berikut:
    </div>

    <table class="grade-table">
        <thead>
            <tr>
                <th class="col-no">No.</th>
                <th>Aktivitas Yang Dinilai</th>
                <th class="col-nilai">Nilai (Angka)</th>
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
                <td class="col-nilai">{{ $nilai }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align: right; margin-bottom: 5px;">
        Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <table class="signature-table">
        <tr>
            <td width="50%">
                Mengetahui,<br>
                <span style="font-weight: bold;">{{ $app->position->skpd->jabatan_pejabat ?? 'Kepala Dinas' }}</span><br>
                {{ $app->position->skpd->nama_dinas }}
                
                <div class="sign-space"></div> <span class="text-bold" style="text-decoration: underline;">
                    {{ $app->position->skpd->nama_pejabat ?? '........................................' }}
                </span><br>
                NIP. {{ $app->position->skpd->nip_pejabat ?? '....................' }}
            </td>

            <td width="50%">
                Pembimbing Lapangan<br>
                <br> <div class="sign-space"></div> <span class="text-bold" style="text-decoration: underline;">{{ $app->mentor->name }}</span><br>
                NIP. ...........................
            </td>
        </tr>
    </table>

</body>
</html>