<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'mentee_id',
        'mentor_id',
        'pasangan_id',
        'bintang_ketersediaan',
        'bintang_penjelasan',
        'bintang_empati',
        'bintang_komitmen',
        'rata_rata',
        'hal_positif',
        'saran',
        'rekomendasi',
        'tahun_ajaran',
    ];

    protected $casts = [
        'rata_rata' => 'decimal:2',
    ];

    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function pasangan()
    {
        return $this->belongsTo(Pasangan::class);
    }

    // Hitung rata-rata otomatis sebelum simpan
    protected static function booted(): void
    {
        static::saving(function (Feedback $feedback) {
            $feedback->rata_rata = (
                $feedback->bintang_ketersediaan +
                $feedback->bintang_penjelasan +
                $feedback->bintang_empati +
                $feedback->bintang_komitmen
            ) / 4;
        });
    }
}
