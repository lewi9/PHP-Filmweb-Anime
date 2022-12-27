<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $faker = Factory::create();

        $words = $faker->words(3);
        if (is_array($words)) {
            DB::table('reviews')->insert([
                'user_id' => '1',
                'anime_id' => '1',
                'text' => $faker->realTextBetween(256, 400),
                'title' => implode(' ', $words),
                'cumulate_rating' => 0,
                'rates' => 0,
                'rating' => 0,
            ]);
        }

        $words = $faker->words(3);
        if (is_array($words)) {
            DB::table('reviews')->insert([
                'user_id' => '2',
                'anime_id' => '1',
                'text' => $faker->realTextBetween(256, 400),
                'title' => implode(' ', $words),
                'cumulate_rating' => 20,
                'rates' => 6,
                'rating' => 5,
            ]);
        }
    }
}
