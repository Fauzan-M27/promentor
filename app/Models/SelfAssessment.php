<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SelfAssessment extends Model
{
    use HasFactory;

    protected $table = 'self_assessment';

    protected $fillable = [
        'user_id',
        'pendaftaran_id',
        'q1_kemampuan_akademik',
        'q2_kemampuan_menjelaskan',
        'q3_komunikasi_empati',
        'q4_ketersediaan_waktu',
        'q5_pengalaman_kepemimpinan',
        'q6_motivasi',
        'kelebihan',
        'kelemahan',
        'rencana_jika_kesulitan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
