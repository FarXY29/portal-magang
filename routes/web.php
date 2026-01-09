<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\AdminKotaController;
use App\Http\Controllers\AdminSkpdController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\PembimbingController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\KepalaDinasController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\Auth\RegisterPembimbingController;
use App\Http\Controllers\GoogleAuthController;
use App\Models\InternshipPosition;
use App\Http\Controllers\CertificateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. HALAMAN PUBLIK (Landing Page dengan Pencarian) ---
Route::get('/', [MagangController::class, 'index'])->name('home');

// Halaman Detail Lowongan (Publik)
Route::get('/lowongan', [MagangController::class, 'index'])->name('lowongan.index');
Route::get('/lowongan/{id}', [MagangController::class, 'show'])->name('lowongan.show');


// --- 2. ROUTE KHUSUS TAMU (GUEST) ---
Route::middleware('guest')->group(function () {
    // Pendaftaran Khusus Dosen/Guru Pembimbing
    Route::get('register-pembimbing', [RegisterPembimbingController::class, 'create'])
                ->name('pembimbing.register');
    Route::post('register-pembimbing', [RegisterPembimbingController::class, 'store']);
});

Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

// --- 3. LOGIKA REDIRECT DASHBOARD ---
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    
    // Redirect user ke dashboard sesuai peran masing-masing
    if ($role == 'admin_kota') return redirect()->route('admin.dashboard');
    if ($role == 'admin_skpd') return redirect()->route('dinas.dashboard');
    if ($role == 'mentor') return redirect()->route('mentor.dashboard');
    if ($role == 'peserta') return redirect()->route('peserta.dashboard');
    if ($role == 'pembimbing') return redirect()->route('pembimbing.dashboard');
    if ($role == 'kepala_dinas') return redirect()->route('kepala_dinas.dashboard');
    
    return view('dashboard'); 
})->middleware(['auth', 'verified'])->name('dashboard');


// --- 4. GROUP ROUTE YANG BUTUH LOGIN (AUTH) ---
Route::middleware('auth')->group(function () {
    
    // A. AREA PESERTA
    Route::middleware(['role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
        Route::get('/dashboard', [MagangController::class, 'dashboard'])->name('dashboard');
        Route::get('/daftar/{id}', [MagangController::class, 'showApplyForm'])->name('daftar.form');
        Route::post('/daftar/{id}', [MagangController::class, 'storeApplication'])->name('daftar');
        Route::resource('logbook', LogbookController::class);
        Route::get('/logbook-print', [LogbookController::class, 'print'])->name('logbook.print');
        Route::get('/sertifikat', [MagangController::class, 'downloadCertificate'])->name('sertifikat');
        Route::get('/download-nilai/{id}', [MagangController::class, 'downloadTranskrip'])->name('download.nilai');


        // ROUTE ABSENSI 
        Route::post('/absen/masuk', [App\Http\Controllers\AttendanceController::class, 'store'])->name('absen.masuk');
        Route::post('/absen/pulang', [App\Http\Controllers\AttendanceController::class, 'clockOut'])->name('absen.pulang');
        Route::post('/absen/izin', [App\Http\Controllers\AttendanceController::class, 'permission'])->name('absen.izin');
    });

    // === TAMBAHKAN ROUTE INI (AJAX CEK KUOTA) ===
    // Ditaruh di luar prefix 'peserta' agar URL-nya tetap /magang/check-availability/...
    Route::middleware(['auth', 'role:peserta'])->group(function () {
        Route::post('/magang/check-availability/{id}', [MagangController::class, 'checkAvailability'])
            ->name('magang.check.availability');
    });

    // B. AREA ADMIN SKPD
    Route::middleware(['role:admin_skpd'])->prefix('dinas')->name('dinas.')->group(function () {
        Route::get('/dashboard', [AdminSkpdController::class, 'index'])->name('dashboard');
        
        // Manajemen Mentor
        Route::get('/mentors', [AdminSkpdController::class, 'indexMentors'])->name('mentors.index');
        Route::post('/mentors', [AdminSkpdController::class, 'storeMentor'])->name('mentors.store');
        Route::get('/mentors/{id}/edit', [AdminSkpdController::class, 'editMentor'])->name('mentors.edit');
        Route::put('/mentors/{id}', [AdminSkpdController::class, 'updateMentor'])->name('mentors.update');
        Route::delete('/mentors/{id}', [AdminSkpdController::class, 'destroyMentor'])->name('mentors.destroy');

        // Manajemen Pelamar & Lowongan
        Route::get('/pelamar', [AdminSkpdController::class, 'applicants'])->name('pelamar');
        Route::post('/pelamar/{id}/terima', [AdminSkpdController::class, 'acceptApplicant'])->name('pelamar.terima');
        Route::post('/pelamar/{id}/tolak', [AdminSkpdController::class, 'rejectApplicant'])->name('pelamar.tolak');
        Route::get('/lowongan', [AdminSkpdController::class, 'indexLowongan'])->name('lowongan.index');
        Route::get('/lowongan/create', [AdminSkpdController::class, 'createLowongan'])->name('lowongan.create');
        Route::post('/lowongan', [AdminSkpdController::class, 'storeLowongan'])->name('lowongan.store');
        Route::get('/lowongan/{id}/edit', [AdminSkpdController::class, 'editLowongan'])->name('lowongan.edit');
        Route::put('/lowongan/{id}', [AdminSkpdController::class, 'updateLowongan'])->name('lowongan.update');
        Route::delete('/lowongan/{id}', [AdminSkpdController::class, 'destroyLowongan'])->name('lowongan.destroy');

        // Monitoring
        Route::get('/peserta-aktif', [AdminSkpdController::class, 'activeInterns'])->name('peserta.index');
        Route::post('/peserta/{id}/assign', [AdminSkpdController::class, 'assignMentor'])->name('peserta.assign');
        Route::get('/peserta/{id}/logbook', [AdminSkpdController::class, 'showLogbooks'])->name('peserta.logbook');
        Route::post('/peserta/{id}/selesai', [AdminSkpdController::class, 'finishIntern'])->name('peserta.selesai');
        Route::post('/logbook/validasi/{id}', [AdminSkpdController::class, 'validateLogbook'])->name('logbook.validasi');
        
        // Laporan Dinas
        Route::get('/laporan/rekap', [AdminSkpdController::class, 'laporanRekap'])->name('laporan.rekap');
        Route::get('/laporan/rekap/print', [AdminSkpdController::class, 'printRekap'])->name('laporan.rekap.print');
        Route::get('/laporan/grading', [AdminSkpdController::class, 'laporanGradingDinas'])->name('laporan.grading');

        // RUTE PENGATURAN PEJABAT
        Route::get('/pengaturan-pejabat', [AdminSkpdController::class, 'editPejabat'])->name('pejabat.edit');
        Route::put('/pengaturan-pejabat', [AdminSkpdController::class, 'updatePejabat'])->name('pejabat.update');

        // ROUTE PENGATURAN JAM (BARU)
        Route::get('/pengaturan', [AdminSkpdController::class, 'settings'])->name('settings');
        Route::put('/pengaturan', [AdminSkpdController::class, 'updateSettings'])->name('settings.update');
    });

    // C. AREA MENTOR
    Route::middleware(['role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
        Route::get('/dashboard', [MentorController::class, 'index'])->name('dashboard');
        Route::get('/mahasiswa/{id}/logbook', [MentorController::class, 'showLogbook'])->name('logbook');
        Route::post('/logbook/validasi/{id}', [MentorController::class, 'validateLogbook'])->name('logbook.validasi');
        Route::get('/mahasiswa/{id}/nilai', [MentorController::class, 'gradingForm'])->name('grading.form');
        Route::post('/mahasiswa/{id}/nilai', [MentorController::class, 'storeGrade'])->name('grading.store');
        // 1. Rute MENAMPILKAN Form (Method GET)
        Route::get('/penilaian/{id}', [MentorController::class, 'formPenilaian'])->name('penilaian');

        // 2. Rute MENYIMPAN Data (Method POST) 
        Route::post('/penilaian/{id}', [MentorController::class, 'simpanNilai'])->name('simpan_nilai');  
        // ROUTE ABSENSI MENTOR
        Route::get('/absensi', [MentorController::class, 'attendance'])->name('attendance.index');
        Route::post('/absensi/{id}/validasi', [MentorController::class, 'validateAttendance'])->name('attendance.validate');
    });

    // D. AREA PEMBIMBING AKADEMIK
    Route::middleware(['role:pembimbing'])->prefix('pembimbing')->name('pembimbing.')->group(function () {
        Route::get('/dashboard', [PembimbingController::class, 'index'])->name('dashboard');
        Route::get('/logbook/{id}', [PembimbingController::class, 'showLogbook'])->name('logbook');
    });


    // E. AREA ADMIN KOTA (SUPER ADMIN)
    Route::middleware(['role:admin_kota'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminKotaController::class, 'index'])->name('dashboard');
        
        // Master Data
        Route::get('/skpd', [AdminKotaController::class, 'indexSkpd'])->name('skpd.index');
        Route::get('/skpd/create', [AdminKotaController::class, 'create'])->name('skpd.create');
        Route::post('/skpd', [AdminKotaController::class, 'store'])->name('skpd.store');
        Route::get('/skpd/{id}/edit', [AdminKotaController::class, 'edit'])->name('skpd.edit');
        Route::put('/skpd/{id}', [AdminKotaController::class, 'update'])->name('skpd.update');
        Route::delete('/skpd/{id}', [AdminKotaController::class, 'destroy'])->name('skpd.destroy');
        
        // Laporan Global & Monitoring
        Route::get('/laporan', [AdminKotaController::class, 'report'])->name('laporan');
        Route::get('/laporan/excel', [AdminKotaController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('/laporan/peserta-global', [AdminKotaController::class, 'laporanPesertaGlobal'])->name('laporan.peserta_global');
        Route::get('/laporan-skpd', [AdminKotaController::class, 'laporanSkpd'])->name('laporan.skpd');
        // Laporan SKPD PDF
        Route::get('/skpd/cetak-pdf', [AdminKotaController::class, 'printSkpd'])->name('skpd.print_pdf');
        Route::get('/laporan/peserta-global/print', [AdminKotaController::class, 'printPesertaGlobal'])
        ->name('laporan.peserta_global.print');
        // Laporan Grading
        Route::get('/laporan-grading', [AdminKotaController::class, 'laporanGrading'])->name('laporan.grading');
        // User Management & Settings
        Route::resource('users', AdminUserController::class);
        Route::get('/monitoring-logbook', [AdminUserController::class, 'logbooks'])->name('users.logbooks');
        Route::get('/monitoring-logbook/{id}', [AdminUserController::class, 'showLogbook'])->name('users.logbooks.show');
        
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });

    // G. PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Route Publik untuk Verifikasi
    Route::get('/verify-certificate/{token}', [CertificateController::class, 'verify'])->name('certificate.verify');
    Route::post('/search-certificate', [CertificateController::class, 'search'])->name('certificate.search');

require __DIR__.'/auth.php';