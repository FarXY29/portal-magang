<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\DailyLog;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminSkpdController extends Controller
{
    /**
     * Dashboard Utama Admin Dinas
     */
    public function index()
    {
        return view('dinas.dashboard');
    }

    // --- MANAJEMEN MENTOR (PEGAWAI DINAS) ---
    
    public function indexMentors()
    {
        $skpdId = Auth::user()->skpd_id;
        // Ambil user dengan role 'mentor' di SKPD ini
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
            'role' => 'mentor', // Role khusus pembimbing lapangan
            'skpd_id' => Auth::user()->skpd_id,
            'nik' => $request->nip // Kita simpan NIP di kolom NIK saja
        ]);

        return back()->with('success', 'Akun Pembimbing Lapangan berhasil dibuat.');
    }

    public function editMentor($id)
    {
        // Pastikan hanya bisa edit mentor milik dinas sendiri
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

    public function acceptApplicant($id)
    {
        // 1. Cari data lamaran beserta data posisinya
        $app = Application::with('position')->findOrFail($id);
        
        // 2. Cek Kuota Posisi
        if ($app->position->kuota <= 0) {
            return back()->with('error', 'Gagal menerima! Kuota untuk posisi ini sudah habis (0).');
        }

        // 3. Kurangi Kuota
        $app->position->decrement('kuota');

        // 4. Update Status Peserta & Kirim Email (Opsional jika setup email aktif)
        $app->update([
            'status' => 'diterima', 
            'tanggal_mulai' => now(),
            'mentor_id' => null // Mentor harus di-assign manual nanti
        ]);

        return back()->with('success', 'Peserta diterima! Kuota posisi berkurang menjadi ' . $app->position->kuota);
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

    public function storeLowongan(Request $request)
    {
        $request->validate([
            'judul_posisi' => 'required',
            'required_major' => 'required',
            'deskripsi' => 'required',
            'kuota' => 'required|numeric',
            'batas_daftar' => 'required|date',
        ]);

        InternshipPosition::create([
            'skpd_id' => Auth::user()->skpd_id,
            'judul_posisi' => $request->judul_posisi,
            'required_major' => $request->required_major,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota,
            'batas_daftar' => $request->batas_daftar,
            'status' => 'buka'
        ]);

        return redirect()->route('dinas.lowongan.index')->with('success', 'Lowongan berhasil dibuat!');
    }

    // --- FITUR EDIT LOWONGAN (BARU) ---
    public function editLowongan($id)
    {
        // Pastikan hanya bisa edit lowongan milik dinas sendiri
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

        $request->validate([
            'judul_posisi' => 'required',
            'required_major' => 'required',
            'deskripsi' => 'required',
            'kuota' => 'required|numeric',
            'batas_daftar' => 'required|date',
            'status' => 'required|in:buka,tutup'
        ]);

        $loker->update([
            'judul_posisi' => $request->judul_posisi,
            'required_major' => $request->required_major,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota,
            'batas_daftar' => $request->batas_daftar,
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
        ->orderBy('status', 'asc') // Aktif dulu baru Selesai
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
            'tanggal_selesai' => now()
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