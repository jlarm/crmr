<?php

declare(strict_types=1);

use App\Enums\Role;

it('has correct values', function (): void {
    expect(Role::ADMIN->value)->toBe('admin')
        ->and(Role::CONSULTANT->value)->toBe('consultant');
});

it('has correct labels', function (): void {
    expect(Role::ADMIN->label())->toBe('Admin')
        ->and(Role::CONSULTANT->label())->toBe('Consultant');
});

it('can get all cases', function (): void {
    $cases = Role::cases();

    expect($cases)->toHaveCount(2)
        ->and($cases)->toContain(Role::ADMIN)
        ->and($cases)->toContain(Role::CONSULTANT);
});

it('can be instantiated from string values', function (): void {
    expect(Role::from('admin'))->toBe(Role::ADMIN)
        ->and(Role::from('consultant'))->toBe(Role::CONSULTANT);
});

it('throws exception for invalid value', function (): void {
    Role::from('invalid');
})->throws(ValueError::class);

it('returns null for invalid with tryFrom', function (): void {
    expect(Role::tryFrom('invalid'))->toBeNull();
});

it('can be compared', function (): void {
    expect(Role::ADMIN === Role::ADMIN)->toBeTrue()
        ->and(Role::ADMIN === Role::CONSULTANT)->toBeFalse();
});
