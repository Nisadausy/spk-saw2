<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'sekolah_asal',
        'jenis_kelamin',
        'no_telepon',
        'alamat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tes()
    {
        return $this->hasMany(Tes::class);
    }
}
