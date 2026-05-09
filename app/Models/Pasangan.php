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
        'mentee_id',
        'prodi',
        'tahun_ajaran',
        'status',
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }
}
