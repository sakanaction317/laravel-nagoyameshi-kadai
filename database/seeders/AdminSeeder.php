<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Admin::where('email', 'admin@example.com')->exists()) {
            $admin = new Admin();
            $admin->email = 'admin@example.com';
            $admin->password = Hash::make('nagoyameshi');
            $admin->save();
        }

        // 新しいアカウント
        Admin::create([
            'email' => 'admin_review@example.com',
            'password' => Hash::make('admin_password'),
        ]);

        User::create([
            'name' => '例 太郎',
            'kana' => 'レイ タロウ',
            'email' => 'user_review@example.com',
            'password' => Hash::make('user_password'),
            'postal_code' => '2222222',
            'address' => '兵庫県神戸市',
            'phone_number' => '09011111111',
        ]);
    }
}
