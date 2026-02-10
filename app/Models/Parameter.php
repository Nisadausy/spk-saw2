<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parameter extends Model
{
    protected $table = 'tes';
    protected $primaryKey = 'tes_id'; // sesuaikan kalau beda

    protected $fillable = [
        'siswa_id',
        // minat_bakat, tinggi_badan, berat_badan, buta_warna, nilai_akademik, dst
        // sesuaikan sama kolom tabel kamu
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'siswa_id');
    }
}
