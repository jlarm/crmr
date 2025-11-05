<?php

declare(strict_types=1);

use App\Enums\Rating;

it('has correct vaules', function (): void {
    expect(Rating::HOT->value)->toBe('hot')
        ->and(Rating::WARM->value)->toBe('warm');
});

it('has correct labels', function (): void {
    expect(Rating::HOT->label())->toBe('Hot')
        ->and(Rating::WARM->label())->toBe('Warm');
});

it('can get all cases', function (): void {
    $cases = Rating::cases();

    expect($cases)->toHaveCount(3)
        ->and($cases)->toContain(Rating::HOT)
        ->and($cases)->toContain(Rating::WARM);
});

it('can be instantiated from string values', function (): void {
    expect(Rating::from('hot'))->toBe(Rating::HOT)
        ->and(Rating::from('warm'))->toBe(Rating::WARM);
});

it('throws exception for invalid values', function (): void {
    Rating::from('invalid');
})->throws(ValueError::class);

it('returns null for invalid with tryFrom', function (): void {
    expect(Rating::tryFrom('invalid'))->toBeNull();
});

it('can be compared', function (): void {
    expect(Rating::HOT === Rating::HOT)->toBeTrue()
        ->and(Rating::WARM === Rating::WARM)->toBeTrue();
});
