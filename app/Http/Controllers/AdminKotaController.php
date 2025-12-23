<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $skpds = Skpd::with(['positions.applications'])->get();
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

    public function edit($id)
    {
        $skpd = Skpd::findOrFail($id);
        return view('admin.skpd.edit', compact('skpd'));
    }

    public function update(Request $request, $id)
    {
        $skpd = Skpd::findOrFail($id);

        $request->validate([
            'nama_dinas' => 'required|string|max:255',
            // Ignore ID saat ini untuk validasi unique
            'kode_unit_kerja' => 'required|string|max:50|unique:skpds,kode_unit_kerja,'.$skpd->id, 
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $skpd->update([
            'nama_dinas' => $request->nama_dinas,
            'kode_unit_kerja' => $request->kode_unit_kerja,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('admin.skpd.index')->with('success', 'Data SKPD berhasil diperbarui!');
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

    // --- FITUR BARU: LAPORAN GLOBAL SEMUA PESERTA (SUPER ADMIN) ---
    // public function laporanPesertaGlobal()
    // {
    //     // Ambil semua aplikasi yang diterima/selesai dari SELURUH dinas
    //     $allInterns = Application::with(['user', 'position.skpd'])
    //         ->whereIn('status', ['diterima', 'selesai'])
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('admin.laporan.peserta_global', compact('allInterns'));
    // }

        public function laporanSkpd()
    {
        // Ambil semua data SKPD
        $skpds = Skpd::orderBy('nama_dinas', 'asc')->get();
        
        return view('admin.laporan_skpd', compact('skpds'));
    }

    public function printSkpd()
    {
        // 1. Ambil Data
        $skpds = Skpd::with(['positions.applications'])->orderBy('nama_dinas', 'asc')->get();

        // 2. Setup PDF
        // Load view yang baru kita buat tadi
        $pdf = Pdf::loadView('admin.pdf.skpd', compact('skpds'));

        // Setup Kertas: A4 Portrait agar kolom alamat muat lega
        $pdf->setPaper('a4', 'portrait');

        // 3. Output (Stream biar bisa preview dulu di browser, kalau mau langsung download ganti jadi ->download())
        return $pdf->stream('Laporan-Master-SKPD.pdf');
    }

    public function laporanPesertaGlobal(Request $request)
    {
        // 1. Siapkan Query Dasar
        $query = Application::with(['user', 'position.skpd'])
                    ->whereIn('status', ['diterima', 'selesai']);

        // 2. Filter Berdasarkan SKPD (Lokasi Magang)
        if ($request->has('skpd_id') && $request->skpd_id != '') {
            $query->whereHas('position.skpd', function($q) use ($request) {
                $q->where('id', $request->skpd_id);
            });
        }

        // 3. Filter Berdasarkan Instansi (Kampus/Sekolah)
        if ($request->has('instansi') && $request->instansi != '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('asal_instansi', $request->instansi);
            });
        }

        // 4. Filter Berdasarkan Nama Posisi (Programming, Admin, dll)
        if ($request->has('posisi') && $request->posisi != '') {
            $query->whereHas('position', function($q) use ($request) {
                $q->where('judul_posisi', 'like', '%' . $request->posisi . '%');
            });
        }

        // Ambil Data
        $allInterns = $query->orderBy('created_at', 'desc')->get();

        // --- DATA UNTUK DROPDOWN FILTER ---
        // Ambil list semua SKPD untuk dropdown
        $listSkpd = Skpd::orderBy('nama_dinas', 'asc')->get();
        
        // Ambil list semua Instansi yang unik dari tabel users
        // Hanya ambil user yang pernah melamar (agar list tidak kosong)
        $listInstansi = User::where('role', 'peserta')
                            ->whereNotNull('asal_instansi')
                            ->distinct()
                            ->pluck('asal_instansi');

        return view('admin.laporan.peserta_global', compact('allInterns', 'listSkpd', 'listInstansi'));
    }

    public function printPesertaGlobal(Request $request)
    {
        // Copy-paste logika query yang sama agar hasil cetak sesuai filter
        $query = Application::with(['user', 'position.skpd'])
                    ->whereIn('status', ['diterima', 'selesai']);

        if ($request->has('skpd_id') && $request->skpd_id != '') {
            $query->whereHas('position.skpd', function($q) use ($request) {
                $q->where('id', $request->skpd_id);
            });
        }

        if ($request->has('instansi') && $request->instansi != '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('asal_instansi', $request->instansi);
            });
        }

        if ($request->has('posisi') && $request->posisi != '') {
            $query->whereHas('position', function($q) use ($request) {
                $q->where('judul_posisi', 'like', '%' . $request->posisi . '%');
            });
        }

        // Urutkan berdasarkan SKPD agar rapi di laporan PDF
        $allInterns = $query->get()->sortBy(function($query){
            return $query->position->skpd->nama_dinas;
        });

        $pdf = Pdf::loadView('admin.pdf.laporan_global', compact('allInterns'));
        $pdf->setPaper('a4', 'landscape'); 

        return $pdf->stream('Laporan-Global-Peserta.pdf');
    }
}