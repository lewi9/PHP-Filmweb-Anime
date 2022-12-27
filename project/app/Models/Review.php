<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'text',
        'user_id',
        'anime_id',
        'cumulate_rating',
        'rates',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<mixed>
     */
    protected $attributes = [
        'cumulate_rating' => 0,
        'rates' => 0,
        'rating' => 0,
    ];
}
