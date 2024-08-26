<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'penulis',
        'category_id',
        'isibuku',
        'cover_image',
        'sinopsis',
        'halaman',
        'rating',
        'audio_length',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pages' => 'integer',
        'rating' => 'float',
        'audio_length' => 'string',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userGoogle()
    {
        return $this->belongsTo(UsersGoogle::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function audioBook()
    {
        return $this->hasOne(AudioBook::class);
    }

    public function bookLogs()
    {
        return $this->hasMany(BookLog::class);
    }
}

