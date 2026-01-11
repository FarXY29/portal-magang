<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skpd extends Model
{
    use HasFactory;

    // Kita harus mendaftarkan kolom mana saja yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'nama_dinas',
        'kode_unit_kerja',
        'alamat',
        'nama_pejabat',
        'nip_pejabat',
        'jabatan_pejabat',
        'jam_mulai_masuk', 
        'jam_mulai_pulang', 
        'latitude',
        'longitude',
        'ttd_kepala', // Kolom Tanda Tangan Kepala Dinas
    ];

    // Relasi: Satu SKPD punya banyak Posisi Magang
    public function positions()
    {
        return $this->hasMany(InternshipPosition::class);
    }
    
    // Relasi: Satu SKPD punya banyak User (Admin Dinas/Mentor)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function applications()
    {
        // Menghubungkan SKPD langsung ke Application melewati InternshipPosition
        return $this->hasManyThrough(
            \App\Models\Application::class,
            \App\Models\InternshipPosition::class,
            'skpd_id',                // Foreign key di tabel internship_positions
            'internship_position_id', // Foreign key di tabel applications
            'id',                     // Local key di tabel skpds
            'id'                      // Local key di tabel internship_positions
        );
    }
}