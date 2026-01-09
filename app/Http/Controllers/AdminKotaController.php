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
    public function index()
    {
        $totalSkpd = Skpd::count();
        $totalUser = User::count();
        $recentSkpds = Skpd::latest()->take(5)->get();

        $skpdStats = Skpd::with(['positions.applications'])->get()->map(function($dinas) {
            $name = $dinas->nama_dinas;
            $words = explode(' ', $name);
            $limit = ceil(count($words) / 2);
            
            $formattedName = count($words) > 2 
                ? [implode(' ', array_slice($words, 0, $limit)), implode(' ', array_slice($words, $limit))]
                : $name;

            return [
                'name' => $formattedName,
                'count' => $dinas->positions->flatMap->applications->count()
            ];
        });

        $allSkpds = $skpdStats->sortBy('name'); 
        $chartLabels = $allSkpds->pluck('name')->values()->toArray();
        $chartData = $allSkpds->pluck('count')->values()->toArray();
        
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
            'email' => $request->email_admin,
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
        $positions = $dinas->positions;
        $applications = $positions->flatMap->applications;
        
        $totalPelamar = $applications->count();
        $totalDiterima = $applications->whereIn('status', ['diterima', 'selesai'])->count();
        $totalPosisi = $positions->count();
        
        // --- PROSES DATA (LOGIKA TAMBAHAN) ---
        // Menghitung berapa persen pelamar yang berhasil diterima (Efektivitas Seleksi)
        $seleksiRate = $totalPelamar > 0 ? round(($totalDiterima / $totalPelamar) * 100, 1) : 0;
        
        // Menghitung rata-rata pelamar per posisi untuk melihat instansi terpopuler
        $avgPelamar = $totalPosisi > 0 ? round($totalPelamar / $totalPosisi, 1) : 0;

        return [
            'nama_dinas' => $dinas->nama_dinas,
            'lowongan_aktif' => $positions->where('status', 'buka')->count(),
            'total_pelamar' => $totalPelamar,
            'total_magang' => $totalDiterima,
            'seleksi_rate' => $seleksiRate . '%', // Hasil Proses
            'avg_peminat' => $avgPelamar . ' orang/posisi', // Hasil Proses
        ];
    });

    $laporan = $laporan->sortByDesc('total_pelamar');
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

        // 2. Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 3. Filter Berdasarkan Tanggal (Periode Magang)
        // Logika: Mencari irisan tanggal magang dengan range yang dipilih
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->start_date;
            $end = $request->end_date;
            
            $query->where(function($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                  ->orWhereBetween('tanggal_selesai', [$start, $end]);
            });
        } 
        // Jika hanya diisi "Mulai Magang" (Mencari yg mulai setelah tanggal ini)
        elseif ($request->filled('start_date')) {
            $query->where('tanggal_mulai', '>=', $request->start_date);
        }
        // Jika hanya diisi "Selesai Magang" (Mencari yg selesai sebelum tanggal ini)
        elseif ($request->filled('end_date')) {
            $query->where('tanggal_selesai', '<=', $request->end_date);
        }

        $applications = $query->latest()->get();
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

    public function laporanGrading()
    {
        // 1. Ambil data aplikasi yang sudah memiliki nilai (status selesai/diterima)
        $query = Application::with(['user', 'position.skpd'])
                    ->whereNotNull('nilai_teknis') // Pastikan kolom nilai tidak kosong
                    ->get();

        // 2. PROSES DATA: Menghitung Rata-rata Kategori & Total
        $gradedData = $query->map(function($app) {
            $avg = ($app->nilai_teknis + $app->nilai_disiplin + $app->nilai_perilaku) / 3;
            
            // Penentuan Predikat
            if ($avg >= 86) $predikat = 'Sangat Baik';
            elseif ($avg >= 71) $predikat = 'Baik';
            elseif ($avg >= 56) $predikat = 'Cukup';
            else $predikat = 'Kurang';

            return [
                'nama' => $app->user->name,
                'instansi' => $app->position->skpd->nama_dinas,
                'teknis' => $app->nilai_teknis,
                'disiplin' => $app->nilai_disiplin,
                'perilaku' => $app->nilai_perilaku,
                'rata_rata' => round($avg, 2),
                'predikat' => $predikat
            ];
        });

        // 3. LOGIKA PEMERINGKATAN (Ranking)
        $ranking = $gradedData->sortByDesc('rata_rata')->values()->take(10); // Ambil 10 Besar

        // 4. LOGIKA DISTRIBUSI NILAI
        $distribusi = [
            'Sangat Baik' => $gradedData->where('predikat', 'Sangat Baik')->count(),
            'Baik' => $gradedData->where('predikat', 'Baik')->count(),
            'Cukup' => $gradedData->where('predikat', 'Cukup')->count(),
            'Kurang' => $gradedData->where('predikat', 'Kurang')->count(),
        ];

        // 5. RATA-RATA GLOBAL PER KATEGORI
        $statsGlobal = [
            'avg_teknis' => round($gradedData->avg('teknis'), 1),
            'avg_disiplin' => round($gradedData->avg('disiplin'), 1),
            'avg_perilaku' => round($gradedData->avg('perilaku'), 1),
        ];

        return view('admin.laporan.grading', compact('ranking', 'distribusi', 'statsGlobal'));
    }
}