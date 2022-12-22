<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('animes')->insert([
            'title' => 'Sailor moon',
            'genre' => 'Magical girl, adventure',
            'production_year' => '1992',
            'poster' => 'sailor.jpg',
            'rating' => 7.4,
            'how_much_users_watched' => 20
        ]);
        DB::table('animes')->insert([
            'title' => 'Neon Genesis Evangelion',
            'genre' => 'Apocalyptic, mecha',
            'production_year' => '1995',
            'poster' => 'missing.jpg',
            'rating' => 8.3,
            'how_much_users_watched' => 10
        ]);
        DB::table('animes')->insert([
            'title' => 'One Piece',
            'genre' => 'Adventure',
            'production_year' => '1999',
            'poster' => 'missing.jpg',
            'rating' => 0,
            'how_much_users_watched' => 0
    ]);

    }
}
