<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

test('has users relationship', function (): void {
    $company = Company::factory()->create();

    expect($company->users())->toBeInstanceOf(BelongsToMany::class);
});

test('can attach companies', function (): void {
    $company = Company::factory()->create();
    $users = User::factory(3)->create();

    $company->users()->attach($users);

    expect($company->users)->toHaveCount(3)
        ->and($company->users->first())->toBeInstanceOf(User::class);
});

test('can detach companies', function (): void {
    $company = Company::factory()->create();
    $users = User::factory(3)->create();

    $company->users()->attach($users);
    $company->users()->detach($users->first());

    expect($company->users)->toHaveCount(2);
});

test('can sync users', function (): void {
    $company = Company::factory()->create();
    $users = User::factory(3)->create();

    $company->users()->attach($users);
    $newUsers = User::factory(2)->create();
    $company->users()->sync($newUsers);

    expect($company->users)->toHaveCount(2);
});
