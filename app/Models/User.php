<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'nama',
        'email',
        'password_hash',
        'is_active',
        'must_change_password'
    ];

    protected $hidden = ['password_hash'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    public function guruBk()
    {
        return $this->hasOne(GuruBk::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class, 'uploader_user_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
