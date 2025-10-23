<?php

declare(strict_types=1);

use App\Models\Dealership;
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
    $dealerships = Dealership::factory(3)->create(['user_id' => $user->id]);

    expect($user->dealerships)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Dealership::class);
});
