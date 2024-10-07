<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::where('username', 'admin')->first();
        if ($user) {
            $user->update([
                'password' => Hash::make('123123')
            ]);
        } else {
            User::create([
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('admin')
            ]);
        }
    }
}