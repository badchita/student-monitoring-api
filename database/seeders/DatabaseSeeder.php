<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->insert([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin2022',
            'password' => Hash::make('Admin2022*'),
            'user_type' => 'admin',
            'date_of_birth' => '01/01/2004',
            'date_of_joined' => '10/24/2022',
            'status' => 'V',
            'is_email_verified' => 1,
            'age' => 18,
        ]);
    }
}
