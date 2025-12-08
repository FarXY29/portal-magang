<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\InternshipPosition;
use Illuminate\Support\Facades\Auth;

class KepalaDinasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->skpd_id) return view('dashboard');

        $skpdId = $user->skpd_id;

        $totalLowongan = InternshipPosition::where('skpd_id', $skpdId)->count();
        
        $totalPesertaAktif = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })->where('status', 'diterima')->count();
        
        $totalAlumni = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })->where('status', 'selesai')->count();

        $popularPositions = InternshipPosition::where('skpd_id', $skpdId)
            ->withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->take(5)
            ->get();

        return view('kepala_dinas.dashboard', compact('totalLowongan', 'totalPesertaAktif', 'totalAlumni', 'popularPositions'));
    }

    public function laporanLowongan()
    {
        $skpdId = Auth::user()->skpd_id;
        $lowongans = InternshipPosition::where('skpd_id', $skpdId)->withCount('applications')->get();
        return view('kepala_dinas.laporan_lowongan', compact('lowongans'));
    }

    // --- FOKUS UTAMA: LAPORAN DATA GABUNGAN ---
    public function laporanPeserta()
    {
        $skpdId = Auth::user()->skpd_id;
        
        // Mengambil Data Gabungan dari Tabel: Applications + Users + Positions + Mentors
        $interns = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })
        ->whereIn('status', ['diterima', 'selesai'])
        // Eager Loading (Menggabungkan Data)
        ->with([
            'user',             // Data Mahasiswa (Nama, NIK, Instansi)
            'position',         // Data Posisi Magang
            'mentor',           // Data Pembimbing Lapangan
            'position.skpd'     // Data Dinas (Optional, jika ingin ditampilkan)
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('kepala_dinas.laporan_peserta', compact('interns'));
    }
}