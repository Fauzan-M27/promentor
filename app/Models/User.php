<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim',
        'nidn',
        'prodi',
        'semester',
        'ipk',
        'no_wa',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ipk' => 'decimal:2',
        ];
    }

    // Helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    // Relasi sebagai mahasiswa (pendaftar)
    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class);
    }

    // Relasi sebagai mentor
    public function mentees()
    {
        return $this->hasMany(Pasangan::class, 'mentor_id');
    }

    // Relasi sebagai mentee
    public function mentor()
    {
        return $this->hasOne(Pasangan::class, 'mentee_id');
    }

    // Notifikasi milik user ini
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    // Feedback yang diberikan (sebagai mentee)
    public function feedbackDiberikan()
    {
        return $this->hasMany(Feedback::class, 'mentee_id');
    }

    // Feedback yang diterima (sebagai mentor)
    public function feedbackDiterima()
    {
        return $this->hasMany(Feedback::class, 'mentor_id');
    }

    // Self assessment
    public function selfAssessment()
    {
        return $this->hasOne(SelfAssessment::class);
    }

    // Rata-rata feedback bintang
    public function getRataFeedbackAttribute(): float
    {
        return $this->feedbackDiterima()->avg('rata_rata') ?? 0;
    }
}
