<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\DailyLog;
use Illuminate\Support\Facades\Auth;

class MentorController extends Controller
{
    public function index()
    {
        $mentorId = Auth::id();
        $interns = Application::where('mentor_id', $mentorId)
                    ->whereIn('status', ['diterima', 'selesai'])
                    ->with(['user', 'position'])
                    ->get();

        return view('mentor.dashboard', compact('interns'));
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
}