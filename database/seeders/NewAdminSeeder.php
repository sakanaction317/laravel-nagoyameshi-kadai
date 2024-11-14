<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
