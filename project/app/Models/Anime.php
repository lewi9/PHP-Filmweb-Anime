<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'genre',
        'production_year',
        'poster',
        'description',
        'rating',
        'how_much_users_watched',
        'rates',
        'cumulate_rating',
        'episodes',
    ];
}
