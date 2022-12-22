<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimeUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('anime_users')->insert([
            'user_id' => 1,
            'anime_id' => 1,
            'would_like_to_watch' => true,
            'favorite' => true,
            'rating' => '10',
        ]);
        DB::table('anime_users')->insert([
            'user_id' => 1,
            'anime_id' => 2,
            'would_like_to_watch' => true,
            'favorite' => false,
            'rating' => '0',
        ]);
    }
}
