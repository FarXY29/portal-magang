<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\DailyLog;
use Illuminate\Support\Facades\Auth;

class PembimbingController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa satu almamater yang sedang/selesai magang.
     */
    public function index()
    {
        $user = Auth::user();
        $instansi = $user->asal_instansi;

        // 1. Cek apakah Pembimbing sudah melengkapi profil instansinya
        if (!$instansi) {
            return view('pembimbing.dashboard', [
                'students' => [],
                'instansi' => null,
                'warning' => 'Data "Asal Instansi" Anda belum diisi. Silakan lengkapi di menu Profile agar sistem dapat mendeteksi mahasiswa bimbingan Anda.'
            ]);
        }

        // 2. Ambil data mahasiswa magang yang instansinya SAMA dengan pembimbing
        $students = Application::whereHas('user', function($query) use ($instansi) {
            $query->where('asal_instansi', $instansi);
        })
        ->whereIn('status', ['diterima', 'selesai']) // Hanya tampilkan yang sudah diterima/lulus
        ->with(['user', 'position.skpd'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('pembimbing.dashboard', compact('students', 'instansi'));
    }

    /**
     * Melihat detail logbook mahasiswa (Read Only / Hanya Lihat).
     */
    public function showLogbook($id)
    {
        $app = Application::with(['user', 'position'])->findOrFail($id);
        
        // Security Check: Pastikan pembimbing & mahasiswa benar-benar satu instansi
        // (Mencegah akses URL langsung ke mahasiswa kampus lain)
        if (Auth::user()->asal_instansi !== $app->user->asal_instansi) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data mahasiswa dari instansi lain.');
        }

        $logs = DailyLog::where('application_id', $id)->orderBy('tanggal', 'desc')->get();

        return view('pembimbing.logbook', compact('app', 'logs'));
    }
}