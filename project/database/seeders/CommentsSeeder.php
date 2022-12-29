<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i =0; $i<5; $i++) {
            DB::table('comments')->insert([
                'user_id' => $faker->numberBetween(1, 2),
                'anime_id' => $faker->numberBetween(1, 2),
                'text' => $faker->text(),
                'likes' => $faker->randomDigit(),
                'dislikes' => $faker->randomDigit()
            ]);
        }
    }
}
