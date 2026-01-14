<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\InternshipPosition;
use App\Models\Skpd;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class MagangController extends Controller
{
    /**
     * TAMPILKAN LIST LOWONGAN
     * Logika: Lowongan tetap muncul selamanya selama Admin tidak menutupnya manual (status != tutup).
     * Kita TIDAK mengecek sisa kuota di sini, karena kuota tergantung tanggal yang dipilih user nanti.
     */
    public function index(Request $request)
    {
        // 1. Query Dasar
        // Hapus where('kuota', '>', 0) jika Anda ingin posisi tetap muncul meski penuh di bulan ini
        // Tapi demi kerapian, kita asumsikan 'kuota' adalah KAPASITAS TOTAL (misal: 1), jadi tetap > 0.
        $query = InternshipPosition::with('skpd')
                    ->where('status', 'buka')
                    ->where('kuota', '>', 0); 

        // 2. Filter Dinas
        if ($request->has('skpd_id') && $request->skpd_id != '') {
            $query->where('skpd_id', $request->skpd_id);
        }

        // 3. Filter Posisi
        if ($request->has('posisi') && $request->posisi != '') {
            $query->where('judul_posisi', 'like', '%' . $request->posisi . '%');
        }

        // 4. Search Global
        if ($request->has('search') && $request->search != '') {
             $search = $request->search;
             $query->where(function($q) use ($search) {
                 $q->where('judul_posisi', 'like', "%{$search}%")
                   ->orWhereHas('skpd', function($sq) use ($search) {
                       $sq->where('nama_dinas', 'like', "%{$search}%");
                   });
             });
        }

        // Pagination
        $lowongans = $query->latest()->paginate(9);
        $lowongans->appends($request->query()); 

        // Data Pendukung View
        $skpds = Skpd::orderBy('nama_dinas', 'asc')->get();
        $totalSkpd = Skpd::count();
        $totalLowongan = InternshipPosition::where('status', 'buka')->count();
        $totalAlumni = Application::where('status', 'selesai')->count();

        return view('welcome', compact(
            'lowongans', 'skpds', 
            'totalSkpd', 'totalLowongan', 'totalAlumni'
        )); 
    }

    /**
     * FORM APPLY
     */
    public function showApplyForm($id)
    {
        $position = InternshipPosition::with('skpd')->findOrFail($id);
        $user = Auth::user();

        // 1. Validasi Jurusan
        $syaratJurusan = strtolower($position->required_major);
        $jurusanPelamar = strtolower($user->major);
        if (!str_contains($syaratJurusan, 'semua jurusan') && !str_contains($syaratJurusan, $jurusanPelamar)) {
            return redirect()->route('home')->with('error', "Posisi ini khusus jurusan: {$position->required_major}.");
        }

        // 2. Cek Kuota Master (Kapasitas Ruangan)
        // Jika kapasitas ruangan 0, berarti memang ditutup selamanya.
        if ($position->kuota <= 0) {
            return redirect()->route('home')->with('error', 'Lowongan ini sedang ditutup (Kapasitas 0).');
        }

        return view('peserta.apply', compact('position'));
    }

    /**
     * PROSES LAMARAN (INTI LOGIKA HOTEL BOOKING)
     */
    public function storeApplication(Request $request, $id)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'surat' => 'required|mimes:pdf|max:2048',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $reqStart = $request->tanggal_mulai;
        $reqEnd   = $request->tanggal_selesai;

        // 2. Cek Double Submit (User sama, Posisi sama, Status Aktif)
        $existingApp = Application::where('user_id', $user->id)
                        ->where('internship_position_id', $id)
                        ->whereIn('status', ['pending', 'diterima'])
                        ->exists();

        if ($existingApp) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda sudah melamar posisi ini.');
        }

        // 3. --- LOGIKA HOTEL / BENTROK TANGGAL ---
        $position = InternshipPosition::findOrFail($id);
        $kapasitasMaksimal = $position->kuota; // Misal: 1

        // Kita hitung: Ada berapa orang yang SEDANG MAGANG (Diterima/Pending)
        // di rentang tanggal yang beririsan dengan permintaan user?
        // Rumus Irisan: (StartA <= EndB) AND (EndA >= StartB)
        
        $bentrokCount = Application::where('internship_position_id', $id)
            ->whereIn('status', ['diterima', 'pending']) // Hitung yang pending juga biar aman
            ->where(function($q) use ($reqStart, $reqEnd) {
                $q->where('tanggal_mulai', '<=', $reqEnd)
                  ->where('tanggal_selesai', '>=', $reqStart);
            })
            ->count();

        // CONTOH KASUS SESUAI PERMINTAAN:
        // Kapasitas = 1.
        // Si A: 1 Nov - 30 Nov (Sudah ada di DB).
        
        // Kasus B: User request 1 Des - 31 Des.
        // Cek: (1 Nov <= 31 Des) AND (30 Nov >= 1 Des) ??? -> TRUE AND FALSE -> FALSE (Tidak Bentrok).
        // Hasil $bentrokCount = 0.
        // 0 < 1 -> LOLOS.
        
        // Kasus C: User request 15 Nov - 15 Des.
        // Cek: (1 Nov <= 15 Des) AND (30 Nov >= 15 Nov) ??? -> TRUE AND TRUE -> TRUE (Bentrok).
        // Hasil $bentrokCount = 1.
        // 1 >= 1 -> DITOLAK.

        if ($bentrokCount >= $kapasitasMaksimal) {
             return redirect()->back()->with('error', 
                "Gagal! Kuota penuh untuk tanggal tersebut. Sudah ada {$bentrokCount} orang terjadwal di periode yang beririsan dengan tanggal pilihan Anda."
             );
        }

        // 4. Simpan Data (JIKA LOLOS)
        $suratPath = $request->file('surat')->store('documents/surat', 'public');

        Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $id,
            'cv_path' => '-', 
            'surat_pengantar_path' => $suratPath,
            'status' => 'pending',
            'tanggal_mulai' => $reqStart,
            'tanggal_selesai' => $reqEnd,
        ]);

        return redirect()->route('peserta.dashboard')->with('success', 'Lamaran berhasil dikirim! Slot tanggal aman.');
    }

    /**
     * Dashboard Peserta
     */
    public function dashboard()
    {
        $user = Auth::user();

        $myApplications = Application::where('user_id', $user->id)
                            ->with(['position.skpd'])
                            ->latest()
                            ->get();

        $activeApp = Application::where('user_id', $user->id)
                        ->where('status', 'diterima')
                        ->with('position.skpd')
                        ->first();

        // Cek Absensi Hari Ini
        $attendanceToday = null;
        $jamKerja = null;

        if ($activeApp) {
            $attendanceToday = Attendance::where('application_id', $activeApp->id)
                                ->where('date', Carbon::now()->format('Y-m-d'))
                                ->first();
            $jamKerja = $activeApp->position->skpd;
        }

        return view('peserta.dashboard', compact('myApplications', 'activeApp', 'attendanceToday', 'jamKerja'));
    }

    // ... (Sisa method downloadCertificate dll sama seperti sebelumnya) ...
    public function downloadCertificate()
    {
        $user = Auth::user();
        
        $finishedApp = Application::where('user_id', $user->id)
                        ->where('status', 'selesai')
                        ->with(['position.skpd', 'user'])
                        ->latest('updated_at')
                        ->first();

        if (!$finishedApp) {
            return back()->with('error', 'Anda belum menyelesaikan program magang manapun.');
        }

        if (empty($user->nik) || empty($user->asal_instansi)) {
            return redirect()->route('profile.edit')->with('error', 'Mohon lengkapi NIK dan Asal Instansi sebelum download sertifikat.');
        }

        $pdf = Pdf::loadView('pdf.sertifikat', ['app' => $finishedApp]);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('Sertifikat-Magang-'.$user->name.'.pdf');
    }

    /**
     * AJAX CHECK AVAILABILITY
     * Dipanggil oleh JavaScript di form apply untuk cek kuota real-time.
     */
    public function checkAvailability(Request $request, $id)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $reqStart = $request->start;
        $reqEnd = $request->end;

        $position = InternshipPosition::findOrFail($id);
        $kapasitasMaksimal = $position->kuota;

        // Query untuk mencari peserta yang bentrok
        // Kita simpan query builder-nya dulu agar bisa digunakan untuk count() dan get()
        $conflictingAppsQuery = Application::where('internship_position_id', $id)
            ->whereIn('status', ['diterima', 'pending'])
            ->where(function($q) use ($reqStart, $reqEnd) {
                $q->where('tanggal_mulai', '<=', $reqEnd)
                  ->where('tanggal_selesai', '>=', $reqStart);
            });

        // Hitung jumlah yang bentrok
        $bentrokCount = $conflictingAppsQuery->count();

        $isAvailable = $bentrokCount < $kapasitasMaksimal;

        if ($isAvailable) {
            return response()->json([
                'status' => 'available',
                'message' => "Kuota Tersedia! (Terisi: {$bentrokCount} dari {$kapasitasMaksimal} kursi)",
                'class' => 'text-green-600 bg-green-50 border-green-200'
            ]);
        } else {
            // JIKA PENUH: Cari peserta terakhir yang selesai magang
            // Ambil data peserta yang bentrok, urutkan berdasarkan tanggal selesai paling akhir (descending)
            $lastParticipant = $conflictingAppsQuery->orderBy('tanggal_selesai', 'desc')->first();
            
            $suggestionMessage = "";
            $suggestionDate = "";
            $suggestionDateText = "";

            if ($lastParticipant) {
                // Ambil tanggal selesai peserta tsb
                $finishDate = Carbon::parse($lastParticipant->tanggal_selesai);
                
                // Tanggal kosong adalah besoknya (H+1)
                $nextAvailableDate = $finishDate->copy()->addDay();

                $suggestionDate = $nextAvailableDate->format('Y-m-d');
                $suggestionDateText = $nextAvailableDate->translatedFormat('d F Y');

                $suggestionMessage = " Kuota Penuh untuk rentang tanggal ini. Sudah ada {$bentrokCount} peserta terjadwal sampai " . $finishDate->translatedFormat('d F Y') . ".";
            } else {
                $suggestionMessage = " Kuota Penuh untuk rentang tanggal ini.";
            }

            return response()->json([
                'status' => 'full',
                'message' => $suggestionMessage,
                'class' => 'text-red-600 bg-red-50 border-red-200',
                // Data tambahan untuk frontend (tombol saran)
                'suggestion_date' => $suggestionDate, 
                'suggestion_text' => $suggestionDateText
            ]);
        }
    }

    public function downloadTranskrip($id)
    {
        // Ambil data aplikasi milik peserta yang sedang login
        $app = Application::where('id', $id)
                    ->where('user_id', Auth::id()) // Keamanan: Pastikan data milik user sendiri
                    ->firstOrFail();

        // Validasi: Cek apakah sudah dinilai dan status selesai
        if ($app->status !== 'selesai' || !$app->nilai_rata_rata) {
            return back()->with('error', 'Transkrip belum tersedia. Tunggu penilaian dari mentor.');
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdf.transkrip_nilai', compact('app'));
        return $pdf->stream('Transkrip-Magang-'.$app->user->name.'.pdf');
    }
}