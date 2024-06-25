<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;

    protected $table = "posts";

    protected $fillable = ["title", "description", "postUrl", "userId"];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
