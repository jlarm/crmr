<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->create([
            'name' => 'Joe Lohr',
            'email' => 'jlohr@autorisknow.com',
            'password' => Hash::make('password'),
            'role' => Role::ADMIN,
        ]);

        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
        ]);

        $users = User::all();
        $companies = Company::all();

        // Attach random companies to each user (1-5 companies per user)
        $users->each(function (User $user) use ($companies): void {
            $user->companies()->attach(
                $companies->random(random_int(1, 5))->pluck('id')
            );
        });

        // Ensure admin has access to some companies
        $admin->companies()->attach(
            $companies->random(10)->pluck('id')
        );
    }
}
