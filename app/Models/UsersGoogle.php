<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class UsersGoogle extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'users-google';
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'google_token',
        'google_refresh_token'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'user_id', 'id');  // Hubungkan dengan model Bookmark
    }
}
