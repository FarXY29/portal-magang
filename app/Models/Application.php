<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'internship_position_id',
        'cv_path',
        'surat_pengantar_path',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'mentor_id',
        // --- TAMBAHAN BARU ---
        'nilai_angka',
        'predikat',
        'catatan_mentor'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function position() { return $this->belongsTo(InternshipPosition::class, 'internship_position_id'); }
    public function mentor() { return $this->belongsTo(User::class, 'mentor_id'); }
    public function logs() { return $this->hasMany(DailyLog::class); }
}