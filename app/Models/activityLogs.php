<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLogs extends Model
{
    protected $table = 'activity_log';
    protected $primaryKey = 'log_id';
    
    // Konfigurasi Timestamp:
    // Karena di database hanya ada created_at, kita matikan updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'aksi',
        // 'created_at' akan diisi otomatis oleh Laravel
    ];

    /** RELASI */
    // Log ini milik User siapa?
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}