<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        DB::table('articles')->insert([
            'title' => implode(' ', $faker->words(3)),
            'text' => $faker->realTextBetween(500, 5000),
            'likes' => 10,
            'dislikes' => 3
        ]);
        DB::table('articles')->insert([
            'title' => implode(' ', $faker->words(3)),
            'text' => $faker->realTextBetween(500, 5000),
            'likes' => 1,
            'dislikes' => 5
        ]);
        DB::table('articles')->insert([
            'title' => implode(' ', $faker->words(3)),
            'text' => $faker->realTextBetween(500, 5000),
            'likes' => 0,
            'dislikes' => 0
        ]);
    }
}
