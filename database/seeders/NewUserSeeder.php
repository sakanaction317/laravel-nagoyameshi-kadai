<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class NewUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => '永瀬拓也',
            'kana' => 'ナガセタクヤ',
            'email' => 'test@example.com',
            'password' => 'password111',
            'postal_code' => '3456789',
            'address' => '京都府',
            'phone_number' => '09034567890',
            'occupation' => 'エンジニア',
        ]);
    }
}
