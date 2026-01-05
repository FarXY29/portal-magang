<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat Magang</title>
    <style>
        body { 
            font-family: 'Times New Roman', serif; 
            text-align: center; 
            border: 10px double #0F766E; 
            padding: 40px;
            height: 90%;
        }
        .header { margin-bottom: 40px; }
        h1 { font-size: 36px; color: #0F766E; margin: 0; text-transform: uppercase; }
        h3 { font-size: 18px; margin-top: 5px; font-weight: normal; }
        .content { margin-top: 40px; font-size: 16px; line-height: 1.6; }
        .name { font-size: 28px; font-weight: bold; margin: 15px 0; text-decoration: underline; }
        .grade-box { 
            margin: 20px auto; 
            border: 2px solid #0F766E; 
            padding: 10px; 
            width: 50%;
            font-weight: bold;
            font-size: 18px;
        }
        .footer { margin-top: 80px; width: 100%; }
        .sign-box { float: right; width: 40%; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sertifikat Penyelesaian</h1>
        <h3>Nomor: {{ $app->id }}/MGG/BJM/{{ date('Y') }}</h3>
    </div>
    
    <div class="content">
        <p>Diberikan kepada:</p>
        <div class="name">{{ $app->user->name }}</div>
        <p>
            NIK: {{ $app->user->nik ?? '-' }} <br>
            Instansi: {{ $app->user->asal_instansi ?? 'Umum' }}
        </p>
        
        <p>Telah menyelesaikan program <strong>Magang / Praktik Kerja Lapangan</strong><br>
        di lingkungan Pemerintah Kota Banjarmasin pada unit kerja:</p>
        
        <h2 style="margin: 10px 0;">{{ $app->position->skpd->nama_dinas }}</h2>
        
        Periode: {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d F Y') }} s.d. {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d F Y') }}</p>
        
        <!-- BAGIAN NILAI DINAMIS -->
        <div class="grade-box">
            Predikat: {{ $app->predikat ?? 'BAIK' }}
        </div>
        <!-- -------------------- -->
    </div>

    <div class="footer">
        <div class="sign-box">
            <p>Banjarmasin, {{ date('d F Y') }}</p>
            <p>Kepala Dinas,</p>
            <br><br><br><br>
            <p><strong>_______________________</strong></p>
            <p>NIP. ...................................</p>
        </div>
    </div>
</body>
</html>