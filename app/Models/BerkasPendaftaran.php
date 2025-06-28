<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'berkas_pendaftaran';

    protected $fillable = [
        'id_user',
        'kk_file',
        'akta_file',
        'piagam_file',
        'foto',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
