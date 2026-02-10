<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Jurusan extends Model
{
    protected $table = 'jurusan';
    protected $primaryKey = 'jurusan_id';

    protected $fillable = [
        'nama_jurusan',
        'deskripsi',
        'prospek_kerja',
        'fasilitas_sekolah',
        'is_active',
    ];

    public function guruBk(): HasOne
    {
        return $this->hasOne(GuruBk::class, 'jurusan_id', 'jurusan_id');
    }
}
