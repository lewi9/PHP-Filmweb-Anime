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
            'text' => 'I was the sailor, I miss that time'
        ]);
        DB::table('comments')->insert([
            'author_id' => '2',
            'anime_id' => '1',
            'text' => 'I love moon!'
        ]);
        DB::table('comments')->insert([
            'author_id' => '2',
            'anime_id' => '2',
            'text' => 'Mecha Gurenge!'
        ]);
        DB::table('comments')->insert([
            'author_id' => '1',
            'anime_id' => '1',
            'text' => 'Very pretty girls'
        ]);
    }
}
