<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class AdminSettingController extends Controller
{
    public function index()
    {
        // Ambil semua setting dan ubah jadi array key => value
        $settings = Setting::all()->pluck('value', 'key');
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // 1. Simpan Nama Aplikasi
        Setting::updateOrCreate(
            ['key' => 'app_name'],
            ['value' => $request->input('app_name')]
        );

        // 2. Simpan Pengumuman
        Setting::updateOrCreate(
            ['key' => 'announcement'],
            ['value' => $request->input('announcement')]
        );

        return back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}