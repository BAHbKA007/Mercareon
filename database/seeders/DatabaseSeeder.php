<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            'name' => 'Johann Schneider',
            'email' => 'j.schneider@gemuesering.de',
            'password' => \Hash::make('123'),
        ]);
    }
}
