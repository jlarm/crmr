<?php

declare(strict_types=1);

use App\Enums\Status;

it('has correct values', function (): void {
    expect(Status::ACTIVE->value)->toBe('active');
});

it('has correct labels for all statuses', function (): void {
    foreach (Status::cases() as $case) {
        expect($case->label())->toBeString()->not->toBeEmpty();
    }
});

it('can get all cases', function (): void {
    $cases = Status::cases();

    expect($cases)->toHaveCount(3)
        ->and($cases)->toContain(Status::ACTIVE);
});

it('can be instantiated from string values', function (): void {
    expect(Status::from('active'))->toBe(Status::ACTIVE);
});

it('throws exception for invalid values', function (): void {
    Status::from('invalid');
})->throws(ValueError::class);

it('returns null for invalid with tryFrom', function (): void {
    expect(Status::tryFrom('invalid'))->toBeNull();
});

it('can be compared', function (): void {
    expect(Status::ACTIVE === Status::ACTIVE)->toBeTrue()
        ->and(Status::ACTIVE === Status::INACTIVE)->toBeFalse();
});
