<?php

declare(strict_types=1);

use App\Models\Company;

test('to array', function (): void {
    $company = Company::factory()->create()->refresh();

    expect(array_keys($company->toArray()))
        ->toBe([
            'id',
            'name',
            'address',
            'city',
            'state',
            'zip',
            'phone',
            'status',
            'rating',
            'type',
            'created_at',
            'updated_at',
        ]);
});
