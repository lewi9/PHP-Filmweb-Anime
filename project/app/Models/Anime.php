<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
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

    /**
     * The model's default values for attributes.
     *
     * @var array<mixed>
     */
    protected $attributes = [
        'rating' => 0,
        'how_much_users_watched' => 0,
        'rates' => 0,
        'cumulate_rating' => 0,
    ];
}
