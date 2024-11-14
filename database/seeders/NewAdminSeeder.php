<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class NewAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newAdmin = new Admin(); 
        $newAdmin->email = 'newadmin@example.com'; 
        $newAdmin->password = Hash::make('newpassword'); 
        $newAdmin->save();
    }
}
