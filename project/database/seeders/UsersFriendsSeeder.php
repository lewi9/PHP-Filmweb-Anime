<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersFriendsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users_friends')->insert([
            'user1_id' => '1',
            'user2_id' => '2',
            'is_pending' => false
        ]);
    }
}
