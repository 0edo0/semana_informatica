<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Edwin Admistrador',
            'email' => 'edwin1819@admin.com',
            'password' => Hash::make('76681466'), // Contraseña
            'role' => 'superadmin'
        ]);
    }
}