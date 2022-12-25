<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersSeeder::class);
        $this->call(AnimesSeeder::class);
        $this->call(AnimeUsersSeeder::class);
        $this->call(UsersFriendsSeeder::class);
        $this->call(CommentsSeeder::class);
    }
}
