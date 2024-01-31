<?php

namespace Database\Seeders;

use App\Models\BlogPost;
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
       //  \App\Models\User::factory(1)->create();

        \DB::table('users')->insert([
            [
                'name' => 'Nur Uddin',
                'role' => 'admin',
                'email' => 'nur@gmail.com',
                'password' => \Hash::make('12345678'),
            ]
        ]);

    }
}
