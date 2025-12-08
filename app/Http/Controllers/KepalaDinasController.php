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
        
        // Pastikan user memiliki SKPD (menghindari error jika null)
        if (!$user->skpd_id) {
            return view('dashboard'); // Fallback ke dashboard default jika tidak punya SKPD
        }

        $skpdId = $user->skpd_id;

        // 1. Hitung Statistik Ringkas
        $totalLowongan = InternshipPosition::where('skpd_id', $skpdId)->count();
        
        $totalPesertaAktif = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })->where('status', 'diterima')->count();
        
        $totalAlumni = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })->where('status', 'selesai')->count();

        // 2. Ambil 5 Posisi Paling Diminati (Ini yang sebelumnya Error/Hilang)
        $popularPositions = InternshipPosition::where('skpd_id', $skpdId)
            ->withCount('applications') // Menghitung jumlah pelamar per posisi
            ->orderBy('applications_count', 'desc')
            ->take(5)
            ->get();

        // 3. Kirim semua variabel ke View
        return view('kepala_dinas.dashboard', compact(
            'totalLowongan', 
            'totalPesertaAktif', 
            'totalAlumni', 
            'popularPositions' // <-- Pastikan variabel ini ada di sini
        ));
    }

    // Laporan Lowongan (Read Only)
    public function laporanLowongan()
    {
        $skpdId = Auth::user()->skpd_id;
        $lowongans = InternshipPosition::where('skpd_id', $skpdId)->withCount('applications')->get();
        return view('kepala_dinas.laporan_lowongan', compact('lowongans'));
    }

    // Laporan Peserta (Read Only)
    public function laporanPeserta()
    {
        $skpdId = Auth::user()->skpd_id;
        $interns = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })
        ->whereIn('status', ['diterima', 'selesai'])
        ->with(['user', 'position'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('kepala_dinas.laporan_peserta', compact('interns'));
    }
}