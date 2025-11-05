<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Rating;
use App\Enums\State;
use App\Enums\Status;
use App\Enums\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
final class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->randomElement(State::cases()),
            'zip' => fake()->postcode(),
            'phone' => fake()->phoneNumber(),
            'status' => fake()->randomElement(Status::cases()),
            'rating' => fake()->randomElement(Rating::cases()),
            'type' => fake()->randomElement(Type::cases()),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Status::ACTIVE,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Status::INACTIVE,
        ]);
    }

    public function hot(): static
    {
        return $this->state(fn (array $attributes): array => [
            'rating' => Rating::HOT,
        ]);
    }

    public function warm(): static
    {
        return $this->state(fn (array $attributes): array => [
            'rating' => Rating::WARM,
        ]);
    }

    public function cold(): static
    {
        return $this->state(fn (array $attributes): array => [
            'rating' => Rating::COLD,
        ]);
    }

    public function automotive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => Type::AUTOMOTIVE,
        ]);
    }

    public function rv(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => Type::RV,
        ]);
    }

    public function motorsports(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => Type::MOTORSPORTS,
        ]);
    }

    public function maritime(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => Type::MARITIME,
        ]);
    }

    public function association(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => Type::ASSOCIATION,
        ]);
    }
}
