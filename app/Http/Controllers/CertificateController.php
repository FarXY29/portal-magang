<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Halaman Publik Hasil Scan QR Code
     */
    public function verify($token)
    {
        // Gunakan 'with' untuk memuat User, Position, dan SKPD sekaligus
        // Menghindari query berulang saat view dirender
        $app = Application::with(['user', 'position.skpd', 'mentor']) 
                ->where('token_verifikasi', $token)
                ->where('status', 'selesai')
                ->firstOrFail();

        return view('certificate.verify', compact('app'));
    }

    /**
     * Logic Pencarian Manual (Untuk Admin/Publik)
     */
    public function search(Request $request)
    {
        $keyword = $request->input('nomor_sertifikat');

        $app = Application::where('nomor_sertifikat', $keyword)
                ->where('status', 'selesai')
                ->first();

        if (!$app) {
            return back()->with('error', 'Sertifikat tidak ditemukan atau nomor salah.');
        }

        return redirect()->route('certificate.verify', $app->token_verifikasi);
    }
}