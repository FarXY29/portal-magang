<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'nilai_angka',
        'predikat',
        'nilai_sikap',
        'nilai_disiplin',
        'nilai_kesungguhan',
        'nilai_mandiri',
        'nilai_kerjasama',
        'nilai_ketelitian',
        'nilai_pendapat',
        'nilai_serap_hal_baru',
        'nilai_inisiatif',
        'nilai_kepuasan',
        'nilai_rata_rata',
        'catatan_mentor',
        'nomor_sertifikat',
        'token_verifikasi',
    ];

    // Event Boot untuk generate nomor otomatis saat status diubah jadi selesai
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('status') && $model->status === 'selesai' && empty($model->nomor_sertifikat)) {
                
                // Format: MAGANG-{TAHUN}-{URUTAN}
                $year = date('Y');
                $count = static::whereYear('created_at', $year)->where('status', 'selesai')->count() + 1;
                $number = 'MG-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
                
                $model->nomor_sertifikat = $number;
                $model->token_verifikasi = Str::random(40); // Token acak 40 karakter
            }
        });
    }


    public function user() { return $this->belongsTo(User::class); }
    public function position() { return $this->belongsTo(InternshipPosition::class, 'internship_position_id'); }
    public function mentor() { return $this->belongsTo(User::class, 'mentor_id'); }
    public function logs() { return $this->hasMany(DailyLog::class); }
}