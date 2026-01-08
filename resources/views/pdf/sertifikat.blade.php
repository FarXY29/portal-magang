<!DOCTYPE html>
<html>
<head>
    <div class="title">SERTIFIKAT MAGANG</div>
    <div style="font-size: 12pt; margin-top: -15px; margin-bottom: 20px;">
        Nomor: {{ $app->nomor_sertifikat ?? 'Draft' }}
    </div>
    <style>
        /* Mengatur Halaman Landscape */
        @page {
            size: A4 landscape;
            margin: 10mm; /* Margin kertas */
        }

        body {
            font-family: "Times New Roman", serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Bingkai Sertifikat */
        .container {
            border: 5px double #1a202c; /* Bingkai ganda tebal */
            padding: 40px;
            height: 90%; /* Mengisi halaman */
            position: relative;
            text-align: center;
        }

        /* Elemen Dekorasi */
        .header-text {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .sub-header {
            font-size: 14pt;
            margin-bottom: 30px;
        }

        .title {
            font-size: 36pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #2d3748; /* Abu tua */
            margin: 20px 0;
            font-family: "Helvetica", sans-serif; /* Font judul sedikit beda */
            border-bottom: 2px solid #cbd5e0;
            display: inline-block;
            padding-bottom: 10px;
        }

        .content-text {
            font-size: 14pt;
            margin: 10px 0;
        }

        .candidate-name {
            font-size: 28pt;
            font-weight: bold;
            margin: 20px 0;
            color: #1a202c;
            text-decoration: underline;
        }

        .duration-text {
            font-size: 14pt;
            font-style: italic;
            margin-bottom: 40px;
            color: #555;
        }

        /* Area Tanda Tangan */
        .signature-section {
            width: 100%;
            margin-top: 50px;
            display: table;
        }
        .sig-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 20px;
        }
        .sign-space {
            height: 80px;
        }

        /* Logo (Opsional: Jika ada file logo di public/img/logo.png) */
        .logo {
            width: 80px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="header-text">PEMERINTAH KOTA BANJARMASIN</div>
        <div class="header-text">{{ $app->position->skpd->nama_dinas }}</div>
        
        <br>

        <div class="title">SERTIFIKAT MAGANG</div>

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

        <div class="signature-section">
            <div class="sig-col">
                Mengetahui,<br>
                <span style="font-weight: bold;">{{ $app->position->skpd->jabatan_pejabat ?? 'Kepala Dinas' }}</span>
                <br><br>
                <div class="sign-space"></div>
                <span style="font-weight: bold; text-decoration: underline;">
                    {{ $app->position->skpd->nama_pejabat ?? '................................' }}
                </span><br>
                NIP. {{ $app->position->skpd->nip_pejabat ?? '....................' }}
            </div>

            <div class="sig-col">
                Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Pembimbing Lapangan
                <br><br>
                <div class="sign-space"></div>
                <span style="font-weight: bold; text-decoration: underline;">{{ $app->mentor->name }}</span><br>
                NIP. ...........................
            </div>
        </div>

        <<div style="position: absolute; bottom: 40px; left: 40px; text-align: center;">
            <img src="data:image/svg+xml;base64, {{ base64_encode(QrCode::format('svg')->size(100)->generate(route('certificate.verify', $app->token_verifikasi ?? 'invalid'))) }} ">
            <br>
            <span style="font-size: 8pt;">Scan untuk verifikasi</span>
        </div>

    </div>

</body>
</html>