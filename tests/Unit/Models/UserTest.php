<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

test('to array', function (): void {
    $user = User::factory()->create()->refresh();

    expect(array_keys($user->toArray()))
        ->toBe([
            'id',
            'name',
            'email',
            'email_verified_at',
            'role',
            'two_factor_confirmed_at',
            'created_at',
            'updated_at',
        ]);
});

test('has companies relationship', function (): void {
    $user = User::factory()->create();

    expect($user->companies())->toBeInstanceOf(BelongsToMany::class);
});

test('can attach companies', function (): void {
    $user = User::factory()->create();
    $companies = Company::factory(3)->create();

    $user->companies()->attach($companies);

    expect($user->companies)->toHaveCount(3)
        ->and($user->companies->first())->toBeInstanceOf(Company::class);
});

test('can detach companies', function (): void {
    $user = User::factory()->create();
    $companies = Company::factory(3)->create();

    $user->companies()->attach($companies);
    $user->companies()->detach($companies->first());

    expect($user->companies)->toHaveCount(2);
});

test('can sync companies', function (): void {
    $user = User::factory()->create();
    $companies = Company::factory(3)->create();

    $user->companies()->attach($companies);
    $newCompanies = Company::factory(2)->create();
    $user->companies()->sync($newCompanies);

    expect($user->companies)->toHaveCount(2);
});
