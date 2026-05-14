<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pasangan extends Model
{
    use HasFactory;

    protected $table = 'pasangan';

    protected $fillable = [
        'mentor_id',
        'mentee_nama',
        'mentee_nim',
        'mentee_no_telp',
        'prodi',
        'tahun_ajaran',
        'status',
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    // Mentee tidak lagi punya relasi ke User (input manual)
    // Akses data mentee langsung dari kolom: mentee_nama, mentee_nim, mentee_no_telp
}
