<?php

declare(strict_types=1);

use App\Models\User;

it('renders dashboard page for authenticated and verified users', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('dashboard'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page->component('dashboard'));
});

it('redirects unauthenticated user to login', function (): void {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});
