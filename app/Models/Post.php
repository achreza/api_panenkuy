<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'contact_person',
        'location',
        'price',
        'expired_time',
        'created_by',
        'picture',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
