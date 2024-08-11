<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => (string) Str::uuid(),
            'name' => 'nama admin',
            'username' => 'admin',
            'phone' => 'telepon admin',
            'email' => 'email admin',
            'password' => 'pastibisa'
        ]);
    }
}
