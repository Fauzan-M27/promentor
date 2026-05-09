<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'dibaca',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tandai sebagai dibaca
    public function tandaiDibaca(): void
    {
        $this->update(['dibaca' => true]);
    }

    // Scope: notifikasi belum dibaca
    public function scopeBelumDibaca($query)
    {
        return $query->where('dibaca', false);
    }
}
