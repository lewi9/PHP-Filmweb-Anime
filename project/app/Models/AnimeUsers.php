<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimeUsers extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'anime_id',
        'would_like_to_watch',
        'favorite',
        'rating',
    ];
}
