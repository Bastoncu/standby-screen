<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin Borusan',
            'email' => 'borusan@info.com',
            'password' => Hash::make('borusan123'),
            
        ]);
    }
}
