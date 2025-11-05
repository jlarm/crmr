<?php

declare(strict_types=1);

use App\Enums\Type;

it('has correct value', function (): void {
    expect(Type::AUTOMOTIVE->value)->toBe('automotive');
});

it('has correct labels for all types', function (): void {
    foreach (Type::cases() as $case) {
        expect($case->label())->toBeString()->not->toBeEmpty();
    }
});

it('can get all cases', function (): void {
    $cases = Type::cases();

    expect($cases)->toHaveCount(5)
        ->and($cases)->toContain(Type::AUTOMOTIVE);
});

it('can be instantiated from string value', function (): void {
    expect(Type::from('automotive'))->toBe(Type::AUTOMOTIVE);
});

it('trows exception for invalid value', function (): void {
    Type::from('invalid');
})->throws(ValueError::class);

it('returns null for invalid with tryFrom', function (): void {
    expect(Type::tryFrom('invalid'))->toBeNull();
});

it('can be compared', function (): void {
    expect(Type::AUTOMOTIVE === Type::AUTOMOTIVE)->toBeTrue()
        ->and(Type::AUTOMOTIVE === Type::RV)->toBeFalse();
});
