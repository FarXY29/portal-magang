<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\InternshipPosition;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class MagangController extends Controller
{
    /**
     * Menampilkan daftar lowongan di halaman depan (opsional jika ingin halaman terpisah).
     */
    public function index()
    {
        $lowongans = InternshipPosition::with('skpd')->where('status', 'buka')->paginate(9);
        return view('welcome', compact('lowongans')); 
    }

    public function show($id)
    {
        // Logika detail lowongan jika diperlukan
    }

    /**
     * Dashboard utama peserta magang.
     * Menampilkan status semua lamaran yang pernah diajukan.
     */
    public function dashboard()
    {
        $myApplications = Application::where('user_id', Auth::id())
                            ->with(['position.skpd'])
                            ->latest()
                            ->get();

        return view('peserta.dashboard', compact('myApplications'));
    }

    /**
     * Menampilkan form upload berkas pendaftaran.
     * Termasuk validasi jurusan (Linearitas).
     */
    public function showApplyForm($id)
    {
        $position = InternshipPosition::with('skpd')->findOrFail($id);
        $user = Auth::user();

        // --- VALIDASI LINEARITAS JURUSAN ---
        $syaratJurusan = strtolower($position->required_major);
        $jurusanPelamar = strtolower($user->major);

        // Jika syarat bukan 'semua jurusan' DAN jurusan pelamar tidak ada di dalam string syarat
        // Contoh: Syarat "Teknik Informatika, SI". Pelamar "Akuntansi". -> DITOLAK.
        if (!str_contains($syaratJurusan, 'semua jurusan') && !str_contains($syaratJurusan, $jurusanPelamar)) {
            return redirect()->route('home')->with('error', "Maaf, posisi ini khusus untuk jurusan: {$position->required_major}. Jurusan Anda ($user->major) tidak sesuai.");
        }

        return view('peserta.apply', compact('position'));
    }

    /**
     * Memproses penyimpanan lamaran magang.
     */
    public function storeApplication(Request $request, $id)
    {
        $user = Auth::user();

        // 1. Validasi Input (Hanya Surat Pengantar, CV dihapus sesuai permintaan)
        $request->validate([
            'surat' => 'required|mimes:pdf|max:2048',
        ]);

        // 2. Cek Double Submit (Tidak boleh melamar di posisi yang sama 2x jika statusnya masih proses)
        $existingApp = Application::where('user_id', $user->id)
                        ->where('internship_position_id', $id)
                        ->whereIn('status', ['pending', 'diterima'])
                        ->exists();

        if ($existingApp) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda sudah melamar posisi ini sebelumnya dan sedang diproses/aktif.');
        }

        // 3. Upload File
        $suratPath = $request->file('surat')->store('documents/surat', 'public');

        // 4. Simpan ke Database
        Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $id,
            'cv_path' => '-', // Dummy value karena kolom tidak boleh kosong
            'surat_pengantar_path' => $suratPath,
            'status' => 'pending',
            'tanggal_mulai' => null,
            'tanggal_selesai' => null,
        ]);

        return redirect()->route('peserta.dashboard')->with('success', 'Lamaran berhasil dikirim! Silakan cek status secara berkala.');
    }

    /**
     * Download Sertifikat Magang (PDF).
     * Hanya bisa diakses jika status lamaran 'selesai'.
     */
    public function downloadCertificate()
    {
        $user = Auth::user();
        
        // Cari magang terakhir yang sudah selesai
        $finishedApp = Application::where('user_id', $user->id)
                        ->where('status', 'selesai')
                        ->with(['position.skpd', 'user'])
                        ->latest('updated_at') // Ambil yang paling baru selesai
                        ->first();

        if (!$finishedApp) {
            return back()->with('error', 'Anda belum menyelesaikan program magang manapun.');
        }

        // Cek kelengkapan profil sebelum cetak
        if (empty($user->nik) || empty($user->asal_instansi)) {
            return redirect()->route('profile.edit')->with('error', 'Mohon lengkapi NIK dan Asal Instansi di profil sebelum mendownload sertifikat.');
        }

        $pdf = Pdf::loadView('pdf.sertifikat', ['app' => $finishedApp]);
        
        // Set ukuran kertas (Opsional, default A4 Portrait)
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat-Magang-'.$user->name.'.pdf');
    }
}