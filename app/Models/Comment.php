<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'comment',
        "comment_by",
        "post_id",
    ];
    
    use HasFactory;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function commentBy()
    {
        return $this->belongsTo(User::class, "comment_by");
    }

}