<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'username' => 'johndoe1',
            'country' => 'UK',
            'name' => 'John Doe',
            'email' => 'john.doe@gmail.com',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'username' => 'janedoe',
            'country' => 'US',
            'name' => 'Jane Doe',
            'email' => 'jd@gmail.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
