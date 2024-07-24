<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "user_id"
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}