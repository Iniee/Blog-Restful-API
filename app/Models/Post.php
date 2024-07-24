<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "user_id",
        "content",
        "blog_id"
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedByUser($userId)
    {
        return $this->likes()->where('liked_by', $userId)->exists();
    }
    
    public function commentByUser($userId)
    {
        return $this->comments()->where('comment_by', $userId)->exists();
    }
    
    public function user()
    {
        return $this->hasOne(User::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}