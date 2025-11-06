<?php

declare(strict_types=1);

use App\Models\Company;
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

it('filters companies by search term', function (): void {
    $user = User::factory()->create();

    Company::factory()->create(['name' => 'Acme Corporation']);
    Company::factory()->create(['name' => 'Globex Industries']);
    Company::factory()->create(['name' => 'Umbrella Corporation']);

    $response = $this->actingAs($user)
        ->get(route('dashboard', ['search' => 'Acme']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->has('companies.data', 1)
            ->where('companies.data.0.name', 'Acme Corporation')
            ->where('filters.search', 'Acme')
        );
});

it('returns empty results when search term matches no companies', function (): void {
    $user = User::factory()->create();

    Company::factory()->create(['name' => 'Acme Corporation']);

    $response = $this->actingAs($user)
        ->get(route('dashboard', ['search' => 'NonExisting']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->has('companies.data', 0)
            ->where('filters.search', 'NonExisting')
        );
});

it('sorts companies by name in ascending order', function (): void {
    $user = User::factory()->create();

    Company::factory()->create(['name' => 'Acme Corporation']);
    Company::factory()->create(['name' => 'Globex Industries']);
    Company::factory()->create(['name' => 'Umbrella Corporation']);

    $response = $this->actingAs($user)
        ->get(route('dashboard', ['sort_by' => 'name', 'sort_direction' => 'desc']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->where('companies.data.0.name', 'Umbrella Corporation')
            ->where('companies.data.1.name', 'Globex Industries')
            ->where('companies.data.2.name', 'Acme Corporation')
            ->where('filters.sort_by', 'name')
            ->where('filters.sort_direction', 'desc')
        );
});

it('sorts companies by in in ascending order by default', function (): void {
    $user = User::factory()->create();

    Company::factory()->create(['name' => 'Globex Industries']);
    Company::factory()->create(['name' => 'Acme Corporation']);
    Company::factory()->create(['name' => 'Umbrella Corporation']);

    $response = $this->actingAs($user)
        ->get(route('dashboard'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->where('companies.data.0.name', 'Acme Corporation')
            ->where('companies.data.1.name', 'Globex Industries')
            ->where('companies.data.2.name', 'Umbrella Corporation')
            ->where('filters.sort_by', 'name')
            ->where('filters.sort_direction', 'asc')
        );
});

it('sorts companies by status', function (): void {
    $user = User::factory()->create();

    Company::factory()->active()->create(['name' => 'Acme Corporation']);
    Company::factory()->inactive()->create(['name' => 'Globex Industries']);

    $response = $this->actingAs($user)
        ->get(route('dashboard', ['sort_by' => 'status', 'sort_direction' => 'asc']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->where('filters.sort_by', 'status')
            ->where('filters.sort_direction', 'asc')
        );
});

it('sorts companies by rating', function (): void {
    $user = User::factory()->create();

    Company::factory()->hot()->create(['name' => 'Acme Corporation']);
    Company::factory()->cold()->create(['name' => 'Globex Industries']);

    $response = $this->actingAs($user)
        ->get(route('dashboard', ['sort_by' => 'rating', 'sort_direction' => 'asc']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->where('filters.sort_by', 'rating')
            ->where('filters.sort_direction', 'asc')
        );
});

it('sorts companies by type', function (): void {
    $user = User::factory()->create();

    Company::factory()->automotive()->create(['name' => 'Acme Corporation']);
    Company::factory()->rv()->create(['name' => 'Umbrella Corporation']);

    $response = $this->actingAs($user)
        ->get(route('dashboard', ['sort_by' => 'type', 'sort_direction' => 'asc']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->where('filters.sort_by', 'type')
            ->where('filters.sort_direction', 'asc')
        );
});

it('paginates companies with custom per_page value', function (): void {
    $user = User::factory()->create();

    Company::factory(30)->create();

    $response = $this->actingAs($user)
        ->get(route('dashboard', ['per_page' => 25]));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->has('companies.data', 25)
            ->where('companies.per_page', 25)
            ->where('companies.total', 30)
            ->where('filters.per_page', '25')
        );
});

it('uses default pagination of 10 items per page', function (): void {
    $user = User::factory()->create();

    Company::factory(30)->create();

    $response = $this->actingAs($user)
        ->get(route('dashboard'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->has('companies.data', 10)
            ->where('companies.per_page', 10)
            ->where('companies.total', 30)
            ->where('filters.per_page', 10)
        );
});

it('combines search, sorting, and pagination filters', function (): void {
    $user = User::factory()->create();

    Company::factory()->create(['name' => 'Acme Alpha']);
    Company::factory()->create(['name' => 'Acme Beta']);
    Company::factory()->create(['name' => 'Acme Charlie']);
    Company::factory()->create(['name' => 'Umbrella Corporation']);

    $response = $this->actingAs($user)
        ->get(route('dashboard', [
            'search' => 'Acme',
            'sort_by' => 'name',
            'sort_direction' => 'asc',
            'per_page' => 25,
        ]));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->has('companies.data', 3)
            ->where('companies.data.0.name', 'Acme Alpha')
            ->where('companies.data.1.name', 'Acme Beta')
            ->where('companies.data.2.name', 'Acme Charlie')
            ->where('filters.search', 'Acme')
            ->where('filters.sort_by', 'name')
            ->where('filters.sort_direction', 'asc')
            ->where('filters.per_page', '25')
        );
});

it('returns correct pagination metadata structure', function (): void {
    $user = User::factory()->create();

    Company::factory(25)->create();

    $response = $this->actingAs($user)
        ->get(route('dashboard', ['per_page' => 10]));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->has('companies.data')
            ->has('companies.current_page')
            ->has('companies.last_page')
            ->has('companies.per_page')
            ->has('companies.total')
            ->has('companies.from')
            ->has('companies.to')
            ->has('companies.links')
            ->where('companies.current_page', 1)
            ->where('companies.last_page', 3)
            ->where('companies.per_page', 10)
            ->where('companies.total', 25)
            ->where('companies.from', 1)
            ->where('companies.to', 10)
        );
});
