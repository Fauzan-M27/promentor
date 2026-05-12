<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'user_id',
        'pengalaman_organisasi',
        'motivation_letter',
        'khs',
        'sertifikat_organisasi',
        'ketersediaan_waktu',
        'status',
        'skor_total',
        'catatan_dosen',
        'tahun_ajaran',
    ];

    // Relasi ke mahasiswa pendaftar
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke penilaian rubrik
    public function penilaian()
    {
        return $this->hasOne(Penilaian::class);
    }

    // Relasi ke self assessment
    public function selfAssessment()
    {
        return $this->hasOne(SelfAssessment::class);
    }

    // Label status dalam bahasa Indonesia
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'Pending',
            'review'    => 'Review',
            'diterima'  => 'Diterima',
            'ditolak'   => 'Ditolak',
            default     => ucfirst($this->status),
        };
    }

    // Badge CSS class
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'badge-warning',
            'review'    => 'badge-info',
            'diterima'  => 'badge-success',
            'ditolak'   => 'badge-danger',
            default     => 'badge-secondary',
        };
    }
}
