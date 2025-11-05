<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->create([
            'name' => 'Joe Lohr',
            'email' => 'jlohr@autorisknow.com',
            'password' => Hash::make('password'),
            'role' => Role::ADMIN,
        ]);

        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
        ]);
    }
}
