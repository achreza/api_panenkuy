<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
    ];

    public function post(){
        return $this->belongsTo(Post::class, 'post_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by','id');
    }
}
