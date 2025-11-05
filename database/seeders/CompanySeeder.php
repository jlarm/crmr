<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

final class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::factory(500)->create();
    }
}
