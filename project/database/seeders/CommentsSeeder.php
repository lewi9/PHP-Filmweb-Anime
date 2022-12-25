<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->insert([
            'author_id' => '1',
            'anime_id' => '1',
            'text' => 'I was the sailor, I miss that time',
            'likes' => 2,
            'dislikes' => 0
        ]);
        DB::table('comments')->insert([
            'author_id' => '2',
            'anime_id' => '1',
            'text' => 'I love the moon!',
            'likes' => 1,
            'dislikes' => 2,
        ]);
        DB::table('comments')->insert([
            'author_id' => '2',
            'anime_id' => '2',
            'text' => 'Mecha Gurenge!',
            'likes' => 2,
            'dislikes' => 1
        ]);
        DB::table('comments')->insert([
            'author_id' => '1',
            'anime_id' => '1',
            'text' => 'Very pretty girls',
            'likes' => 50,
            'dislikes' => 0
        ]);
    }
}
