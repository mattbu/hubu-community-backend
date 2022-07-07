<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments() {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    // 게시글에 좋아요가 달림.
    public function likes() {
        return $this->belongsToMany(User::class, 'likes');
    }

}
