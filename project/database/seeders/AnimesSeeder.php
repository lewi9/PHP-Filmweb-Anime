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
            'genre' => 'Magical girl',
            'production_year' => '1992',
            'poster' => 'sailor.jpg',
            'rating' => 7.4,
            'how_much_users_watched' => 10
        ]);
        DB::table('animes')->insert([
            'title' => 'Neon Genesis Evangelion',
            'genre' => 'Apocalyptic',
            'production_year' => '1995',
            'poster' => 'missing.jpg',
            'rating' => 8.3,
            'how_much_users_watched' => 10
        ]);
    }
}
