<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\DailyLog;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class MentorController extends Controller
{
    public function index()
    {
        $mentorId = Auth::id();

        // 1. Ambil Data Mahasiswa Bimbingan
        $interns = Application::where('mentor_id', $mentorId)
                    ->whereIn('status', ['diterima', 'selesai'])
                    ->with(['user', 'position.skpd'])
                    ->get();

        // 2. HITUNG LOGBOOK PENDING (Untuk Badge Logbook - Opsional jika sudah ada)
        $pendingLogbooks = DailyLog::whereHas('application', function($q) use ($mentorId) {
            $q->where('mentor_id', $mentorId);
        })->where('status_validasi', 'pending')->count();

        // 3. HITUNG ABSENSI PENDING (Untuk Badge Absensi - BARU)
        // Menghitung berapa izin/sakit yang belum disetujui
        $pendingAttendance = Attendance::whereHas('application', function($q) use ($mentorId) {
            $q->where('mentor_id', $mentorId);
        })->where('validation_status', 'pending')->count();

        return view('mentor.dashboard', compact('interns', 'pendingLogbooks', 'pendingAttendance'));
    }

    public function showLogbook($applicationId)
    {
        $app = Application::findOrFail($applicationId);
        if($app->mentor_id != Auth::id()) abort(403);

        $logs = DailyLog::where('application_id', $applicationId)->orderBy('tanggal', 'desc')->get();
        return view('mentor.logbook', compact('app', 'logs'));
    }

    public function validateLogbook(Request $request, $id)
    {
        $log = DailyLog::findOrFail($id);
        if($log->application->mentor_id != Auth::id()) abort(403);

        $log->update([
            'status_validasi' => $request->status,
            'komentar_mentor' => $request->komentar
        ]);

        return back()->with('success', 'Logbook divalidasi.');
    }

    // --- FITUR BARU: PENILAIAN AKHIR ---

    public function gradingForm($id)
    {
        $app = Application::findOrFail($id);
        if($app->mentor_id != Auth::id()) abort(403);

        return view('mentor.grading', compact('app'));
    }

    public function storeGrade(Request $request, $id)
    {
        $app = Application::findOrFail($id);
        if($app->mentor_id != Auth::id()) abort(403);

        $request->validate([
            'nilai_angka' => 'required|numeric|min:0|max:100',
            'catatan' => 'required|string'
        ]);

        // Hitung Predikat Otomatis
        $nilai = $request->nilai_angka;
        $predikat = 'E';
        if ($nilai >= 85) $predikat = 'A (Sangat Baik)';
        elseif ($nilai >= 75) $predikat = 'B (Baik)';
        elseif ($nilai >= 60) $predikat = 'C (Cukup)';
        elseif ($nilai >= 50) $predikat = 'D (Kurang)';

        $app->update([
            'nilai_angka' => $nilai,
            'predikat' => $predikat,
            'catatan_mentor' => $request->catatan
        ]);

        return redirect()->route('mentor.dashboard')->with('success', 'Nilai berhasil disimpan.');
    }

    public function attendance(Request $request)
    {
        $mentorId = Auth::user()->id;
        
        // 1. Tentukan Tanggal yang Dipilih (Default Hari Ini)
        $selectedDate = $request->input('date', date('Y-m-d'));

        // 2. Buat List 7 Hari Terakhir untuk Sidebar
        // Kita gunakan Collection untuk mempermudah loop di View
        $dateList = collect([]);
        for ($i = 0; $i < 7; $i++) {
            $dateList->push(\Carbon\Carbon::now()->subDays($i));
        }

        // 3. Ambil ID Mahasiswa Bimbingan
        $applicationIds = Application::where('mentor_id', $mentorId)
                            ->whereIn('status', ['diterima', 'selesai'])
                            ->pluck('id');

        // 4. Query Absensi Berdasarkan Tanggal yang Dipilih ($selectedDate)
        $attendances = Attendance::whereIn('application_id', $applicationIds)
                    ->where('date', $selectedDate)
                    ->with(['application.user', 'application.position'])
                    ->latest()
                    ->get();

        return view('mentor.attendance', compact('attendances', 'dateList', 'selectedDate'));
    }

    /**
     * PROSES VALIDASI IZIN/SAKIT
     */
    public function validateAttendance(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:approved,rejected',
            'mentor_note' => 'nullable|string'
        ]);

        $attendance = Attendance::findOrFail($id);
        
        // Pastikan yang memvalidasi adalah mentor yang berhak
        if ($attendance->application->mentor_id != Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        $attendance->update([
            'validation_status' => $request->status_validasi,
            'mentor_note' => $request->mentor_note
        ]);

        return back()->with('success', 'Status izin/sakit berhasil diperbarui.');
    }

    public function simpanNilai(Request $request, $id)
    {
        // 1. Validasi 10 Input
        $validated = $request->validate([
            'nilai_sikap' => 'required|numeric|min:0|max:100',
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_kesungguhan' => 'required|numeric|min:0|max:100',
            'nilai_mandiri' => 'required|numeric|min:0|max:100',
            'nilai_kerjasama' => 'required|numeric|min:0|max:100',
            'nilai_ketelitian' => 'required|numeric|min:0|max:100',
            'nilai_pendapat' => 'required|numeric|min:0|max:100',
            'nilai_serap_hal_baru' => 'required|numeric|min:0|max:100',
            'nilai_inisiatif' => 'required|numeric|min:0|max:100',
            'nilai_kepuasan' => 'required|numeric|min:0|max:100',
            'catatan_mentor' => 'nullable|string',
        ]);

        $app = Application::findOrFail($id);

        // 2. Hitung Rata-rata
        $total = $request->nilai_sikap + $request->nilai_disiplin + $request->nilai_kesungguhan + 
                $request->nilai_mandiri + $request->nilai_kerjasama + $request->nilai_ketelitian + 
                $request->nilai_pendapat + $request->nilai_serap_hal_baru + $request->nilai_inisiatif + 
                $request->nilai_kepuasan;
        
        $rataRata = $total / 10;

        // 3. Simpan ke Database
        $app->update(array_merge($validated, [
            'nilai_rata_rata' => $rataRata,
            'status' => 'selesai' // Status berubah jadi selesai agar peserta bisa download sertifikat
        ]));

        return redirect()->route('mentor.dashboard')->with('success', 'Penilaian berhasil disimpan!');
    }

    public function formPenilaian($id)
    {
        // Ambil data aplikasi berdasarkan ID
        // Pastikan aplikasi ini memang dibimbing oleh mentor yang sedang login (Opsional tapi disarankan untuk keamanan)
        $application = Application::findOrFail($id);

        // Cek apakah mentor berhak menilai (misal cek mentor_id)
        if ($application->mentor_id != Auth::id()) {
            abort(403, 'Anda bukan pembimbing peserta ini.');
        }

        return view('mentor.penilaian', compact('application'));
    }
}