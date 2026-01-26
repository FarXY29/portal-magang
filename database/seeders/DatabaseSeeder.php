<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Skpd;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data SKPD Dummy (Contoh)
        $kominfo = Skpd::create([
            'nama_dinas' => 'Dinas Kominfotik Banjarmasin',
            'alamat' => 'Jl. RE Martadinata No.1',
            'kode_unit_kerja' => 'DISKOMINFO-01',
            'latitude' => -3.3194,
            'longitude' => 114.5908
        ]);

        // 2. Buat Akun Admin SKPD (Contoh untuk Kominfo)
        User::create([
            'name' => 'Admin Kominfo',
            'email' => 'kominfo@banjarmasin.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin_skpd',
            'skpd_id' => $kominfo->id,
        ]);
        
        // 3. Buat Akun Peserta Dummy
        User::create([
            'name' => 'Mahasiswa Test',
            'email' => 'mhs@test.com',
            'password' => Hash::make('password123'),
            'role' => 'peserta',
        ]);
    }
}