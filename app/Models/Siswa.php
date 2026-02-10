<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'siswa_id';

    public $incrementing = true;
    protected $keyType = 'int';

    // tabel siswa kamu tidak ada created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'sekolah_asal',
        'jenis_kelamin',
        'no_telepon',
        'alamat',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // relasi ke tabel tes (yang kita pakai model "Parameter")
    public function parameters(): HasMany
    {
        return $this->hasMany(Parameter::class, 'siswa_id', 'siswa_id');
    }
}
