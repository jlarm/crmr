<?php

declare(strict_types=1);

use App\Enums\State;

it('has correct values', function (): void {
    expect(State::ILLINOIS->value)->toBe('IL');
});

it('has correct labels for all states', function (): void {
    foreach (State::cases() as $state) {
        expect($state->label())->toBeString()->not->toBeEmpty();
    }
});

it('can get all cases', function (): void {
    $cases = State::cases();

    expect($cases)->toHaveCount(50)
        ->and($cases)->toContain(State::ILLINOIS);
});

it('can be instantiated from string values', function (): void {
    expect(State::from('IL'))->toBe(State::ILLINOIS);
});

it('throws exception for invalid value', function (): void {
    State::from('invalid');
})->throws(ValueError::class);

it('returns null for invalid with tryFrom', function (): void {
    expect(State::tryFrom('invalid'))->toBeNull();
});

it('can be compared', function (): void {
    expect(State::ILLINOIS === State::ILLINOIS)->toBeTrue()
        ->and(State::ILLINOIS === State::INDIANA)->toBeFalse();
});
