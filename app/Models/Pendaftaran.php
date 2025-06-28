<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'user_id',
        'nama',
        'nisn',
        'nik',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'sekolah_asal',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'no_hp',
        'status_verifikasi',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk status badge
    public function getStatusVerifikasiBadgeAttribute()
    {
        return match ($this->status_verifikasi) {
            'Terverifikasi' => 'success',
            'Ditolak' => 'danger',
            'Belum Diverifikasi' => 'warning',
            default => 'secondary'
        };
    }

    // Accessor untuk TTL otomatis dari tempat_lahir dan tgl_lahir
    public function getTtlLengkapAttribute()
    {
        if ($this->tempat_lahir && $this->tgl_lahir) {
            return $this->tempat_lahir . ', ' . Carbon::parse($this->tgl_lahir)->locale('id')->isoFormat('DD MMMM YYYY');
        }
        return $this->ttl; // fallback ke TTL lama
    }

    // Mutator untuk auto-generate TTL saat save
    // public function setTempatLahirAttribute($value)
    // {
    //     $this->attributes['tempat_lahir'] = $value;
    //     $this->updateTtl();
    // }

    // public function setTanggalLahirAttribute($value)
    // {
    //     $this->attributes['tgl_lahir'] = $value;
    //     $this->updateTtl();
    // }

    // private function updateTtl()
    // {
    //     if (isset($this->attributes['tempat_lahir']) && isset($this->attributes['tgl_lahir'])) {
    //         $tanggal = Carbon::parse($this->attributes['tgl_lahir'])->locale('id')->isoFormat('DD MMMM YYYY');
    //         $this->attributes['ttl'] = $this->attributes['tempat_lahir'] . ', ' . $tanggal;
    //     }
    // }

    // Constants untuk status
    const STATUS_VERIFIKASI = [
        'BELUM_DIVERIFIKASI' => 'Belum Diverifikasi',
        'TERVERIFIKASI' => 'Terverifikasi',
        'DITOLAK' => 'Ditolak'
    ];

    const JENIS_KELAMIN = [
        'LAKI_LAKI' => 'Laki-laki',
        'PEREMPUAN' => 'Perempuan'
    ];
}
