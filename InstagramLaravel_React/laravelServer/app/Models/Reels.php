<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reels extends Model
{
    use HasFactory;

    protected $table = "reels";

    protected $fillable = ["title", "description", "reelsUrl", "userId"];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
