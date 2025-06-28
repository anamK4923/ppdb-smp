<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'dark_mode',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getProfileImageAttribute()
    {
        $profileImage = $this->attributes['profile_image'] ?? null;

        if ($profileImage) {
            return asset('storage/' . $profileImage);
        }

        return asset('images/ame.jpg');
    }

    public function adminlte_image()
    {
        return $this->profile_image;
    }

    // Relasi ke Pendaftaran
    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class);
    }

    // Relasi ke Pengumuman
    public function pengumuman()
    {
        return $this->hasMany(Pengumuman::class);
    }

    // Relasi ke Berkas Pendaftaran
    public function berkasPendaftaran()
    {
        return $this->hasOne(BerkasPendaftaran::class, 'id_user', 'id');
    }
}
