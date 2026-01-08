<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\DailyLog;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSkpdController extends Controller
{
    /**
     * Dashboard Utama Admin Dinas
     */
    public function index()
    {
        $user = Auth::user();
        $skpd = $user->skpd;

        // Ambil ID semua lowongan milik SKPD ini
        $positionIds = InternshipPosition::where('skpd_id', $skpd->id)->pluck('id');

        // 1. WIDGET STATUS (Khusus SKPD ini)
        $stats = Application::whereIn('internship_position_id', $positionIds)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $widget = [
            'pending'   => $stats['pending'] ?? 0,
            'active'    => $stats['diterima'] ?? 0,
            'completed' => $stats['selesai'] ?? 0,
            'rejected'  => $stats['ditolak'] ?? 0,
        ];

        // 2. TOP 5 SEKOLAH/KAMPUS (Khusus yang magang di SKPD ini)
        $topInstansi = DB::table('applications')
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->whereIn('applications.internship_position_id', $positionIds) // Filter Lowongan SKPD
            ->whereIn('applications.status', ['diterima', 'selesai'])
            ->whereNotNull('users.asal_instansi')
            ->select('users.asal_instansi', DB::raw('count(*) as total_peserta'))
            ->groupBy('users.asal_instansi')
            ->orderByDesc('total_peserta')
            ->limit(5)
            ->get();

        // Data lowongan untuk tabel bawah (kode lama Anda mungkin seperti ini)
        $recentPositions = InternshipPosition::where('skpd_id', $skpd->id)->latest()->take(5)->get();

        return view('dinas.dashboard', compact('skpd', 'widget', 'topInstansi', 'recentPositions'));
    }

    // --- MANAJEMEN MENTOR (PEGAWAI DINAS) ---
    
    public function indexMentors()
    {
        $skpdId = Auth::user()->skpd_id;
        $mentors = User::where('skpd_id', $skpdId)->where('role', 'mentor')->get();
        return view('dinas.mentors.index', compact('mentors'));
    }

    public function storeMentor(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nip' => 'nullable' 
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mentor',
            'skpd_id' => Auth::user()->skpd_id,
            'nik' => $request->nip
        ]);

        return back()->with('success', 'Akun Pembimbing Lapangan berhasil dibuat.');
    }

    public function editMentor($id)
    {
        $mentor = User::where('id', $id)
                    ->where('skpd_id', Auth::user()->skpd_id)
                    ->where('role', 'mentor')
                    ->firstOrFail();

        return view('dinas.mentors.edit', compact('mentor'));
    }

    public function updateMentor(Request $request, $id)
    {
        $mentor = User::where('id', $id)
                    ->where('skpd_id', Auth::user()->skpd_id)
                    ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$mentor->id, 
            'nip' => 'nullable|string|max:20',
            'password' => 'nullable|min:6'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nip
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $mentor->update($data);

        return redirect()->route('dinas.mentors.index')->with('success', 'Data mentor berhasil diperbarui.');
    }

    public function destroyMentor($id)
    {
        $user = User::where('id', $id)->where('skpd_id', Auth::user()->skpd_id)->firstOrFail();
        $user->delete();
        return back()->with('success', 'Akun mentor dihapus.');
    }

    // --- MANAJEMEN PELAMAR ---

    public function applicants()
    {
        $skpdId = Auth::user()->skpd_id;
        $applicants = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })->with(['user', 'position'])->orderBy('created_at', 'desc')->get();

        return view('dinas.pelamar', compact('applicants'));
    }

    /**
     * TERIMA PELAMAR (Logika Booking Hotel)
     */
    public function acceptApplicant($id)
    {
        $app = Application::with('position')->findOrFail($id);
        
        // Cek kapasitas (opsional, bisa dihapus jika ingin bypass)
        if ($app->position->kuota <= 0) {
            return back()->with('error', 'Peringatan: Posisi ini memiliki kapasitas 0 (Ditutup).');
        }

        // Update Status (Tanpa mengurangi kuota, Tanpa overwrite tanggal)
        $app->update([
            'status' => 'diterima',
        ]);

        return back()->with('success', 'Peserta diterima! Jadwal telah dikunci sesuai pengajuan.');
    }

    public function rejectApplicant($id)
    {
        $app = Application::findOrFail($id);
        $app->update(['status' => 'ditolak']);
        return back()->with('success', 'Peserta ditolak.');
    }

    // --- MANAJEMEN LOWONGAN ---

    public function indexLowongan()
    {
        $skpdId = Auth::user()->skpd_id;
        $lowongans = InternshipPosition::where('skpd_id', $skpdId)->get();
        return view('dinas.lowongan.index', compact('lowongans'));
    }

    public function createLowongan() { return view('dinas.lowongan.create'); }

    public function editPejabat()
    {
        // Mengambil data SKPD milik user yang sedang login
        $skpd = Auth::user()->skpd;
        
        return view('dinas.profil.edit_pejabat', compact('skpd'));
    }

    /**
     * Memproses Update Data ke Database
     */
    public function updatePejabat(Request $request)
    {
        $request->validate([
            'nama_pejabat' => 'required|string|max:255',
            'nip_pejabat' => 'required|string|max:50',
            'jabatan_pejabat' => 'required|string|max:100',
        ]);

        $skpd = Auth::user()->skpd;

        $skpd->update([
            'nama_pejabat' => $request->nama_pejabat,
            'nip_pejabat' => $request->nip_pejabat,
            'jabatan_pejabat' => $request->jabatan_pejabat,
        ]);

        return back()->with('success', 'Data pejabat penandatangan berhasil diperbarui!');
    }
    public function storeLowongan(Request $request)
    {
        // 1. Hapus Validasi 'judul_posisi' dan 'batas_daftar'
        $request->validate([
            'required_major' => 'required',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|numeric',
        ]);

        InternshipPosition::create([
            'skpd_id' => Auth::user()->skpd_id,
            'judul_posisi' => 'Peserta Magang', // Default Value
            'required_major' => $request->required_major,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota,
            'batas_daftar' => null, // Default NULL
            'status' => 'buka'
        ]);

        return redirect()->route('dinas.lowongan.index')->with('success', 'Lowongan berhasil dibuat!');
    }

    public function editLowongan($id)
    {
        $loker = InternshipPosition::where('id', $id)
                    ->where('skpd_id', Auth::user()->skpd_id)
                    ->firstOrFail();

        return view('dinas.lowongan.edit', compact('loker'));
    }

    public function updateLowongan(Request $request, $id)
    {
        $loker = InternshipPosition::where('id', $id)
                    ->where('skpd_id', Auth::user()->skpd_id)
                    ->firstOrFail();

        // 1. Hapus Validasi 'judul_posisi' dan 'batas_daftar'
        $request->validate([
            // 'judul_posisi' => 'required', // DIHAPUS
            'required_major' => 'required',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|numeric',
            // 'batas_daftar' => 'required|date', // DIHAPUS
            'status' => 'required|in:buka,tutup'
        ]);

        $loker->update([
            'judul_posisi' => 'Peserta Magang', // Default Value
            'required_major' => $request->required_major,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota,
            'batas_daftar' => null, // Default NULL
            'status' => $request->status
        ]);

        return redirect()->route('dinas.lowongan.index')->with('success', 'Lowongan berhasil diperbarui!');
    }

    public function destroyLowongan($id)
    {
        $loker = InternshipPosition::where('id', $id)->where('skpd_id', Auth::user()->skpd_id)->firstOrFail();
        $loker->delete();
        return back()->with('success', 'Lowongan dihapus.');
    }

    // --- MONITORING PESERTA & VALIDASI ---

    public function activeInterns()
    {
        $skpdId = Auth::user()->skpd_id;
        $mentors = User::where('skpd_id', $skpdId)->where('role', 'mentor')->get();

        $interns = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })
        ->whereIn('status', ['diterima', 'selesai'])
        ->with(['user', 'position', 'mentor'])
        ->orderBy('status', 'asc')
        ->get();

        return view('dinas.peserta.index', compact('interns', 'mentors'));
    }

    public function assignMentor(Request $request, $id)
    {
        $app = Application::findOrFail($id);
        if($app->position->skpd_id != Auth::user()->skpd_id) abort(403);

        $app->update(['mentor_id' => $request->mentor_id]);
        return back()->with('success', 'Pembimbing lapangan berhasil ditetapkan.');
    }

    public function finishIntern($id)
    {
        $app = Application::findOrFail($id);
        if($app->position->skpd_id != Auth::user()->skpd_id) abort(403);
        
        $app->update([
            'status' => 'selesai',
        ]);
        
        return back()->with('success', 'Peserta berhasil diluluskan! Sertifikat kini tersedia.');
    }
    
    public function showLogbooks($applicationId)
    {
        $app = Application::with(['user', 'position'])->findOrFail($applicationId);
        if($app->position->skpd_id != Auth::user()->skpd_id) abort(403);

        $logs = DailyLog::where('application_id', $applicationId)->orderBy('tanggal', 'desc')->get();
        return view('dinas.peserta.detail', compact('app', 'logs'));
    }
    
    public function validateLogbook(Request $request, $id)
    {
        $log = DailyLog::findOrFail($id);
        $log->update([
            'status_validasi' => $request->status,
            'komentar_mentor' => $request->komentar ?? null
        ]);
        return back()->with('success', 'Status logbook diperbarui.');
    }

    // --- LAPORAN REKAPITULASI ---
    public function laporanRekap()
    {
        $skpdId = Auth::user()->skpd_id;
        $rekap = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })
        ->whereIn('status', ['diterima', 'selesai'])
        ->with(['user', 'position', 'mentor'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('dinas.laporan.rekap', compact('rekap'));
    }

    public function laporanGradingDinas()
    {
        $skpdId = Auth::user()->skpd_id;

        $query = Application::with(['user', 'position'])
                    ->whereHas('position', fn($q) => $q->where('skpd_id', $skpdId))
                    ->whereNotNull('nilai_teknis')
                    ->get();

        $gradedData = $query->map(function($app) {
            $t = (float) $app->nilai_teknis;
            $d = (float) $app->nilai_disiplin;
            $p = (float) $app->nilai_perilaku;
            $avg = ($t + $d + $p) / 3;

            return [
                'nama' => $app->user->name,
                'posisi' => $app->position->judul_posisi,
                'teknis' => $t,
                'disiplin' => $d,
                'perilaku' => $p,
                'rata_rata' => round($avg, 2),
                'predikat' => $avg >= 86 ? 'Sangat Baik' : ($avg >= 71 ? 'Baik' : 'Cukup')
            ];
        });

        $ranking = $gradedData->sortByDesc('rata_rata')->values();
        
        $distribusi = [
            'Sangat Baik' => $gradedData->where('predikat', 'Sangat Baik')->count(),
            'Baik' => $gradedData->where('predikat', 'Baik')->count(),
            'Cukup' => $gradedData->where('predikat', 'Cukup')->count(),
        ];

        // PERBAIKAN: Menghitung statistik global khusus dinas ini
        $statsGlobal = [
            'avg_teknis' => $gradedData->count() > 0 ? round($gradedData->avg('teknis'), 1) : 0,
            'avg_disiplin' => $gradedData->count() > 0 ? round($gradedData->avg('disiplin'), 1) : 0,
            'avg_perilaku' => $gradedData->count() > 0 ? round($gradedData->avg('perilaku'), 1) : 0,
        ];

        // PERBAIKAN: Sertakan statsGlobal dalam compact()
        return view('dinas.laporan.grading', compact('ranking', 'distribusi', 'statsGlobal'));
    }

    // Pengaturan
    public function settings()
    {
        $skpd = Auth::user()->skpd;
        return view('dinas.settings', compact('skpd'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'jam_mulai_masuk' => 'required',
            'jam_mulai_pulang' => 'required',
        ]);

        $skpd = Auth::user()->skpd;
        
        $skpd->update([
            'jam_mulai_masuk' => $request->jam_mulai_masuk,
            'jam_mulai_pulang' => $request->jam_mulai_pulang,
        ]);

        return back()->with('success', 'Pengaturan jam kerja berhasil diperbarui.');
    }
}