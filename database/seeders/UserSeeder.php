<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Администратор',
            'email' => '1@1.ru',
            'password' => '111111',
            'role' => Role::Admin,
        ]);

        User::create([
            'name' => 'Менеджер',
            'email' => '2@2.ru',
            'password' => '222222',
            'role' => Role::Manager,
        ]);
    }
}
