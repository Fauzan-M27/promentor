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

    // Mentee tidak lagi punya relasi (input manual di tabel pasangan)
    // Mahasiswa yang jadi mentor bisa lihat mentee mereka via: $user->mentees

    // Notifikasi milik user ini
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    // Feedback dihapus - tidak digunakan lagi

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
