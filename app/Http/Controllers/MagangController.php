<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\InternshipPosition;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class MagangController extends Controller
{
    // Halaman Detail Lowongan (Publik)
    public function index() {
        $lowongans = InternshipPosition::with('skpd')->where('status', 'buka')->get();
        return view('welcome', compact('lowongans')); 
    }

    public function show($id) {
        // ... logika detail lowongan jika ada
    }

    // Dashboard Peserta
    public function dashboard()
    {
        $myApplications = Application::where('user_id', Auth::id())->with('position.skpd')->get();
        return view('peserta.dashboard', compact('myApplications'));
    }

    // Menampilkan Form Upload
    public function showApplyForm($id)
    {
        $position = InternshipPosition::with('skpd')->findOrFail($id);
        return view('peserta.apply', compact('position'));
    }

    // Proses Simpan Lamaran
    public function storeApplication(Request $request, $id)
    {
        $user = Auth::user();

        // 1. Validasi Input (Hanya Surat Pengantar)
        $request->validate([
            // 'cv' dihapus dari validasi
            'surat' => 'required|mimes:pdf|max:2048',
        ]);

        // 2. Cek Double Submit
        $existingApp = Application::where('user_id', $user->id)
                        ->where('internship_position_id', $id)
                        ->exists();

        if ($existingApp) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda sudah melamar posisi ini sebelumnya!');
        }

        // 3. Upload File
        $suratPath = $request->file('surat')->store('documents/surat', 'public');

        // 4. Simpan ke Database
        Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $id,
            'cv_path' => '-', // Isi dengan strip karena di database kolom ini wajib diisi
            'surat_pengantar_path' => $suratPath,
            'status' => 'pending'
        ]);

        return redirect()->route('peserta.dashboard')->with('success', 'Lamaran berhasil dikirim! Silakan cek status secara berkala.');
    }

    public function downloadCertificate()
    {
        $user = Auth::user();
        $finishedApp = Application::where('user_id', $user->id)
                        ->where('status', 'selesai')
                        ->with(['position.skpd', 'user'])
                        ->first();

        if (!$finishedApp) {
            return back()->with('error', 'Anda belum menyelesaikan program magang.');
        }

        $pdf = Pdf::loadView('pdf.sertifikat', ['app' => $finishedApp]);
        return $pdf->download('Sertifikat-Magang-'.$user->name.'.pdf');
    }
}