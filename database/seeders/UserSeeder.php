<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(100)->create();

        User::create([
            'name' => '渡部明',
            'kana' => 'ワタナベアキラ',
            'email' => 'new_user@example.com',
            'password' => Hash::make('password123'),
            'postal_code' => '1234567',
            'address' => '東京都葛飾区',
            'phone_number' => '09012345678',
            'occupation' => 'エンジニア',
        ]);
    }
}
