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
}