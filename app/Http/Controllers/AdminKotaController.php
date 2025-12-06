<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class AdminKotaController extends Controller
{
    // Halaman Dashboard Utama Super Admin
    public function index()
    {
        $totalSkpd = Skpd::count();
        $totalUser = User::count();
        $recentSkpds = Skpd::latest()->take(5)->get();

        // --- LOGIKA UNTUK CHART/GRAFIK ---
        // 1. Ambil semua SKPD & hitung pelamarnya
        $skpdStats = Skpd::with(['positions.applications'])->get()->map(function($dinas) {
            return [
                'name' => $dinas->nama_dinas,
                // Hitung jumlah pelamar di semua posisi milik dinas ini
                'count' => $dinas->positions->flatMap->applications->count()
            ];
        });

        // 2. Urutkan dari yang terbanyak, ambil 5 besar
        $topSkpds = $skpdStats->sortByDesc('count')->take(5);

        // 3. Pisahkan Label (Nama) dan Data (Angka) untuk Chart.js
        $chartLabels = $topSkpds->pluck('name')->values()->toArray();
        $chartData = $topSkpds->pluck('count')->values()->toArray();
        
        return view('admin.dashboard', compact('totalSkpd', 'totalUser', 'recentSkpds', 'chartLabels', 'chartData'));
    }

    // --- MANAJEMEN SKPD ---

    public function indexSkpd()
    {
        $skpds = Skpd::all();
        return view('admin.skpd.index', compact('skpds'));
    }

    public function create()
    {
        return view('admin.skpd.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dinas' => 'required|string|max:255',
            'kode_unit_kerja' => 'required|string|max:50|unique:skpds',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'email_admin' => 'required|email|unique:users,email',
            'password_admin' => 'required|min:8',
        ]);

        $skpd = Skpd::create([
            'nama_dinas' => $request->nama_dinas,
            'kode_unit_kerja' => $request->kode_unit_kerja,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        User::create([
            'name' => 'Admin ' . $request->nama_dinas,
            'email' => $request->email,
            'password' => Hash::make($request->password_admin),
            'role' => 'admin_skpd',
            'skpd_id' => $skpd->id,
        ]);

        return redirect()->route('admin.skpd.index')->with('success', 'SKPD Baru & Akun Admin berhasil dibuat!');
    }

    public function destroy($id)
    {
        $skpd = Skpd::findOrFail($id);
        User::where('skpd_id', $skpd->id)->delete();
        $skpd->delete();
        return back()->with('success', 'Data SKPD dan Akun Admin terkait telah dihapus.');
    }

    // --- LAPORAN & EXCEL ---
    
    public function report()
    {
        $laporan = Skpd::with(['positions.applications'])->get()->map(function($dinas) {
            $totalPelamar = $dinas->positions->flatMap->applications->count();
            $totalDiterima = $dinas->positions->flatMap->applications
                            ->whereIn('status', ['diterima', 'selesai'])->count();

            return [
                'nama_dinas' => $dinas->nama_dinas,
                'lowongan_aktif' => $dinas->positions->where('status', 'buka')->count(),
                'total_pelamar' => $totalPelamar,
                'total_magang' => $totalDiterima,
            ];
        });

        $laporan = $laporan->sortByDesc('total_magang');

        return view('admin.laporan', compact('laporan'));
    }

    public function exportExcel()
    {
        return Excel::download(new LaporanExport, 'Laporan-Rekap-Magang-'.date('d-m-Y').'.xlsx');
    }
}