<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'user_id',
        'hasil_seleksi',
        'tanggal'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
