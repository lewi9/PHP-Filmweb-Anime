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
        for ($i=0; $i<3; $i++) {
            $words = $faker->words(3);
            if (is_array($words)) {
                DB::table('articles')->insert([
                    'title' => implode(' ', $words),
                    'text' => $faker->realTextBetween(500, 5000),
                    'likes' => 10,
                    'dislikes' => 3
                ]);
            }
        }
    }
}
