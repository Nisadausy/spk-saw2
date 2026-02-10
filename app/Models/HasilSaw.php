<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilSaw extends Model
{
    protected $table = 'hasil_saw';
    protected $primaryKey = 'hasil_saw_id';

    public $timestamps = false; // karena di proposal pakai create_at, bukan created_at

    protected $fillable = [
        'siswa_id',
        'skor_total',
        'rekom_jurusan',
        'create_at',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'siswa_id');
    }
}
