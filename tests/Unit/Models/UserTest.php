<?php

declare(strict_types=1);

use App\Models\Dealership;
use App\Models\Store;
use App\Models\User;

test('to array', function (): void {
    $user = User::factory()->create()->refresh();

    expect(array_keys($user->toArray()))
        ->toBe([
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at',
        ]);
});

test('has many dealerships', function (): void {
    $user = User::factory()->create();
    $dealership1 = Dealership::factory()->create();
    $dealership2 = Dealership::factory()->create();

    $user->dealerships()->attach($dealership1);
    $user->dealerships()->attach($dealership2);

    expect($user->dealerships)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Dealership::class);
});

test('has many stores', function (): void {
    $user = User::factory()->create();
    $dealership = Dealership::factory()->create();
    $store1 = Store::factory()->create([
        'user_id' => $user->id,
        'dealership_id' => $dealership->id,
    ]);
    $store2 = Store::factory()->create([
        'user_id' => $user->id,
        'dealership_id' => $dealership->id,
    ]);

    expect($user->stores)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Store::class);
});
