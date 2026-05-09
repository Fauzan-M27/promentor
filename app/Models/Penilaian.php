<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'pendaftaran_id',
        'dosen_id',
        'kompetensi_akademik',
        'kemampuan_komunikasi',
        'kepemimpinan',
        'integritas_komitmen',
        'catatan',
    ];

    protected $appends = ['skor_total'];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // Hitung total skor dari 4 dimensi (maks 100)
    public function getSkorTotalAttribute(): int
    {
        return $this->kompetensi_akademik
             + $this->kemampuan_komunikasi
             + $this->kepemimpinan
             + $this->integritas_komitmen;
    }

    // Apakah lulus minimum 70?
    public function getLulusAttribute(): bool
    {
        return $this->skor_total >= 70;
    }
}
