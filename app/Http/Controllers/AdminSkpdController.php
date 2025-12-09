<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\DailyLog;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationAccepted;     
use App\Mail\ApplicationRejected;

class AdminSkpdController extends Controller
{
    public function index()
    {
        return view('dinas.dashboard');
    }

    // --- MANAJEMEN MENTOR ---
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

    public function destroyMentor($id)
    {
        $user = User::where('id', $id)->where('skpd_id', Auth::user()->skpd_id)->firstOrFail();
        $user->delete();
        return back()->with('success', 'Akun mentor dihapus.');
    }

    // --- MANAJEMEN PELAMAR (UPDATE KUOTA DISINI) ---

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
        $app = Application::with(['position', 'user'])->findOrFail($id); // Load user untuk email
        
        if ($app->position->kuota <= 0) {
            return back()->with('error', 'Gagal menerima! Kuota habis.');
        }

        $app->position->decrement('kuota');

        $app->update([
            'status' => 'diterima', 
            'tanggal_mulai' => now(),
            'mentor_id' => null 
        ]);

        // --- KIRIM EMAIL NOTIFIKASI ---
        try {
            Mail::to($app->user->email)->send(new ApplicationAccepted($app));
        } catch (\Exception $e) {
            // Biarkan error email tidak menghentikan proses aplikasi
            // log error jika perlu: \Log::error($e->getMessage());
        }
        // ------------------------------

        return back()->with('success', 'Peserta diterima & notifikasi email dikirim!');
    }

    public function rejectApplicant($id)
    {
        $app = Application::with('user')->findOrFail($id);
        $app->update(['status' => 'ditolak']);

        // --- KIRIM EMAIL NOTIFIKASI ---
        try {
            Mail::to($app->user->email)->send(new ApplicationRejected($app));
        } catch (\Exception $e) {
            // Silent fail
        }
        // ------------------------------

        return back()->with('success', 'Peserta ditolak & notifikasi email dikirim.');
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
            'judul_posisi' => 'required', 'deskripsi' => 'required', 'kuota' => 'required|numeric', 'batas_daftar' => 'required|date',
        ]);
        InternshipPosition::create([
            'skpd_id' => Auth::user()->skpd_id,
            'judul_posisi' => $request->judul_posisi, 'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota, 'batas_daftar' => $request->batas_daftar, 'status' => 'buka'
        ]);
        return redirect()->route('dinas.lowongan.index')->with('success', 'Lowongan berhasil dibuat!');
    }

    public function destroyLowongan($id)
    {
        $loker = InternshipPosition::where('id', $id)->where('skpd_id', Auth::user()->skpd_id)->firstOrFail();
        $loker->delete();
        return back()->with('success', 'Lowongan dihapus.');
    }

    // --- MONITORING PESERTA ---

    public function activeInterns()
    {
        $skpdId = Auth::user()->skpd_id;
        $mentors = User::where('skpd_id', $skpdId)->where('role', 'mentor')->get();

        $interns = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })
        ->whereIn('status', ['diterima', 'selesai'])
        ->with(['user', 'position', 'mentor'])
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
        
        // Note: Saat lulus, kuota biasanya TIDAK kembali (karena sudah terpakai di periode tersebut).
        // Kecuali kebijakannya beda.
        
        $app->update(['status' => 'selesai', 'tanggal_selesai' => now()]);
        return back()->with('success', 'Peserta berhasil diluluskan!');
    }
    
    // Tambahan fungsi showLogbooks untuk Admin Dinas (Read Only)
    public function showLogbooks($applicationId)
    {
        $app = Application::with(['user', 'position'])->findOrFail($applicationId);
        if($app->position->skpd_id != Auth::user()->skpd_id) abort(403);

        $logs = DailyLog::where('application_id', $applicationId)->orderBy('tanggal', 'desc')->get();
        return view('dinas.peserta.detail', compact('app', 'logs'));
    }
    
    // Tambahan fungsi validateLogbook (Admin Dinas juga bisa validasi jika perlu)
    public function validateLogbook(Request $request, $id)
    {
        $log = DailyLog::findOrFail($id);
        $log->update([
            'status_validasi' => $request->status,
            'komentar_mentor' => $request->komentar ?? null
        ]);
        return back()->with('success', 'Status logbook diperbarui.');
    }

    // --- FITUR BARU: LAPORAN REKAPITULASI (ADMIN DINAS) ---
    public function laporanRekap()
    {
        $skpdId = Auth::user()->skpd_id;
        
        // Ambil data peserta (Gabungan Diterima & Selesai)
        $rekap = Application::whereHas('position', function($q) use ($skpdId) {
            $q->where('skpd_id', $skpdId);
        })
        ->whereIn('status', ['diterima', 'selesai'])
        ->with(['user', 'position', 'mentor'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('dinas.laporan.rekap', compact('rekap'));
    }
    
}