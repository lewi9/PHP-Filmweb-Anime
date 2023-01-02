<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimeUsers extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    protected $fillable = [
        'user_id',
        'anime_id',
        'would_like_to_watch',
        'favorite',
        'rating',
        'watched',
        'watched_episodes',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<mixed>
     */
    protected $attributes = [
        'would_like_to_watch' => false,
        'favorite' => false,
        'rating' => '0',
        'watched' => false,
        'watched_episodes' => 0,
    ];
}
