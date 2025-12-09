<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\InternshipPosition;
use App\Models\User;
use App\Models\DailyLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KepalaDinasController extends Controller
{
    /**
     * Dashboard Utama Kepala Dinas
     */
    public function index()
    {
        $user = Auth::user();
        
        // Validasi: Pastikan user memiliki SKPD (menghindari error jika null)
        if (!$user->skpd_id) {
            return view('dashboard'); 
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

        // 2. Ambil 5 Posisi Paling Diminati
        $popularPositions = InternshipPosition::where('skpd_id', $skpdId)
            ->withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->take(5)
            ->get();

        return view('kepala_dinas.dashboard', compact(
            'totalLowongan', 
            'totalPesertaAktif', 
            'totalAlumni', 
            'popularPositions'
        ));
    }

    /**
     * REPORT 1: LAPORAN KETERSEDIAAN LOWONGAN
     */
    public function laporanLowongan()
    {
        $skpdId = Auth::user()->skpd_id;
        
        $lowongans = InternshipPosition::where('skpd_id', $skpdId)
            ->withCount('applications')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kepala_dinas.laporan_lowongan', compact('lowongans'));
    }

    /**
     * REPORT 2: LAPORAN DATA PESERTA (LENGKAP)
     */
    public function laporanPeserta()
    {
        $skpdId = Auth::user()->skpd_id;
        
        $interns = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })
        ->whereIn('status', ['diterima', 'selesai'])
        ->with(['user', 'position', 'mentor', 'position.skpd'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('kepala_dinas.laporan_peserta', compact('interns'));
    }

    /**
     * REPORT 3: LAPORAN DEMOGRAFI (ASAL INSTANSI)
     */
    public function laporanDemografi()
    {
        $skpdId = Auth::user()->skpd_id;
        
        // Hitung jumlah peserta per asal instansi
        $demografi = Application::whereHas('position', function($q) use ($skpdId) {
                $q->where('skpd_id', $skpdId);
            })
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->select('users.asal_instansi', DB::raw('count(*) as total'))
            ->whereIn('applications.status', ['diterima', 'selesai'])
            ->groupBy('users.asal_instansi')
            ->orderBy('total', 'desc')
            ->get();

        return view('kepala_dinas.laporan_demografi', compact('demografi'));
    }

    /**
     * REPORT 4: LAPORAN PENILAIAN AKHIR (TRANSKRIP)
     */
    public function laporanNilai()
    {
        $skpdId = Auth::user()->skpd_id;
        
        // Ambil peserta yang sudah punya nilai
        $scores = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })
        ->whereNotNull('nilai_angka')
        ->with(['user', 'position', 'mentor'])
        ->orderBy('nilai_angka', 'desc')
        ->get();

        return view('kepala_dinas.laporan_nilai', compact('scores'));
    }

    /**
     * REPORT 5: LAPORAN KEAKTIFAN (ABSENSI LOGBOOK)
     */
    public function laporanAbsensi()
    {
        $skpdId = Auth::user()->skpd_id;

        // Ambil semua peserta, hitung total logbook dan logbook valid
        $activity = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })
        ->whereIn('status', ['diterima', 'selesai'])
        ->withCount(['logs as total_log', 'logs as valid_log' => function($q) {
            $q->where('status_validasi', 'disetujui');
        }])
        ->with(['user', 'position'])
        ->orderBy('valid_log', 'desc')
        ->get();

        return view('kepala_dinas.laporan_absensi', compact('activity'));
    }

    /**
     * REPORT 6: LAPORAN STATISTIK PEMINAT
     */
    public function laporanStatistik()
    {
        $skpdId = Auth::user()->skpd_id;

        // Statistik perbandingan Kuota vs Pelamar vs Diterima
        $stats = InternshipPosition::where('skpd_id', $skpdId)
            ->withCount(['applications as total_pelamar', 'applications as diterima' => function($q) {
                $q->where('status', 'diterima')->orWhere('status', 'selesai');
            }])
            ->orderBy('total_pelamar', 'desc')
            ->get();

        return view('kepala_dinas.laporan_statistik', compact('stats'));
    }
}