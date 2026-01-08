<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat Magang</title>
    <style>
        /* 1. Atur Margin Halaman di sini (Area Cetak Aman) */
        @page {
            size: A4 landscape;
            margin: 10mm; /* Kiri/Kanan/Atas/Bawah 10mm */
        }

        /* 2. Reset Body agar tidak ada spasi tambahan */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: "Times New Roman", serif;
            color: #333;
        }

        /* 3. Container Utama (Bingkai) */
        .container {
            /* Tinggi A4 (210mm) - Margin Atas Bawah (20mm) = 190mm 
               Kita set 188mm agar ada toleransi 2mm supaya tidak tembus halaman baru */
            height: 188mm; 
            width: 100%;
            border: 4px double #1a202c; /* Bingkai sedikit ditipiskan */
            box-sizing: border-box; /* Agar padding tidak menambah lebar/tinggi */
            padding: 25px;
            position: relative; /* Penting untuk QR Code absolute */
            text-align: center;
        }

        /* 4. Typography & Spacing (Diperpadat) */
        .header-text {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
            line-height: 1.1;
        }

        .title {
            font-size: 30pt; /* Diperkecil dari 36pt */
            font-weight: bold;
            text-transform: uppercase;
            color: #2d3748;
            margin: 10px 0 5px 0;
            font-family: "Helvetica", sans-serif;
            border-bottom: 2px solid #cbd5e0;
            display: inline-block;
            padding-bottom: 5px;
        }

        .nomor-surat {
            font-size: 10pt;
            margin-bottom: 15px;
            color: #555;
        }

        .content-text {
            font-size: 12pt;
            margin: 5px 0;
        }

        .candidate-name {
            font-size: 24pt; /* Diperkecil dari 28pt */
            font-weight: bold;
            margin: 15px 0;
            color: #1a202c;
            text-decoration: underline;
        }

        .duration-text {
            font-size: 12pt;
            font-style: italic;
            margin-bottom: 25px;
            color: #555;
        }

        /* 5. Layout Tanda Tangan */
        .signature-section {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse; /* Menggunakan tabel agar lebih stabil */
        }
        .signature-section td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 11pt;
        }
        .sign-space {
            height: 65px; /* Ruang tanda tangan pas */
        }
    </style>
</head>
<body>

    <div class="container">
        
        <div style="position: absolute; top: 25px; left: 25px; text-align: center;">
            <img src="data:image/svg+xml;base64, {{ base64_encode(QrCode::format('svg')->size(75)->generate(route('certificate.verify', $app->token_verifikasi ?? 'invalid'))) }} ">
            <br>
            <span style="font-size: 7pt;">Scan Validasi</span>
        </div>

        <div class="header-text">PEMERINTAH KOTA BANJARMASIN</div>
        <div class="header-text">{{ $app->position->skpd->nama_dinas }}</div>
        
        <br>

        <div class="title">SERTIFIKAT MAGANG</div>
        <div class="nomor-surat">
            Nomor: {{ $app->nomor_sertifikat ?? 'Draft' }}
        </div>

        <p class="content-text">Diberikan apresiasi setinggi-tingginya kepada:</p>

        <div class="candidate-name">{{ $app->user->name }}</div>
        
        <p class="content-text">
            Telah menyelesaikan program Praktek Kerja Lapangan (PKL) dengan predikat 
            <strong>{{ $app->nilai_rata_rata >= 85 ? 'SANGAT BAIK' : ($app->nilai_rata_rata >= 70 ? 'BAIK' : 'CUKUP') }}
        </p>

        <p class="duration-text">
            Dilaksanakan mulai tanggal {{ \Carbon\Carbon::parse($app->start_date)->translatedFormat('d F Y') }} 
            sampai dengan {{ \Carbon\Carbon::parse($app->end_date)->translatedFormat('d F Y') }}.
        </p>

        <table class="signature-section">
            <tr>
                <td>
                    Mengetahui,<br>
                    <span style="font-weight: bold;">{{ $app->position->skpd->jabatan_pejabat ?? 'Kepala Dinas' }}</span>
                    <br><br>
                    <div class="sign-space"></div>
                    <span style="font-weight: bold; text-decoration: underline;">
                        {{ $app->position->skpd->nama_pejabat ?? '................................' }}
                    </span><br>
                    NIP. {{ $app->position->skpd->nip_pejabat ?? '....................' }}
                </td>
                <td>
                    Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    Pembimbing Lapangan
                    <br><br>
                    <div class="sign-space"></div>
                    <span style="font-weight: bold; text-decoration: underline;">{{ $app->mentor->name }}</span><br>
                    NIP. ...........................
                </td>
            </tr>
        </table>

    </div>

</body>
</html>