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
        DB::table('comments')->insert([
            'author_id' => '1',
            'anime_id' => '1',
            'text' => $faker->text(),
            'likes' => $faker->randomDigit(),
            'dislikes' => $faker->randomDigit()
        ]);
        DB::table('comments')->insert([
            'author_id' => '2',
            'anime_id' => '1',
            'text' => $faker->text(),
            'likes' => $faker->randomDigit(),
            'dislikes' => $faker->randomDigit(),
        ]);
        DB::table('comments')->insert([
            'author_id' => '2',
            'anime_id' => '2',
            'text' => $faker->text(),
            'likes' => $faker->randomDigit(),
            'dislikes' => $faker->randomDigit()
        ]);
        DB::table('comments')->insert([
            'author_id' => '1',
            'anime_id' => '1',
            'text' => $faker->text(),
            'likes' => $faker->randomDigit()*$faker->randomDigit(),
            'dislikes' => $faker->randomDigit()
        ]);
    }
}
