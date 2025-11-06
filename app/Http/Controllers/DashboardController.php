<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IndexCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController
{
    public function __invoke(IndexCompanyRequest $request): Response
    {
        $query = Company::query();

        /** @var string|null $search */
        $search = $request->validated('search');
        if (is_string($search) && $search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        /** @var string $sortBy */
        $sortBy = $request->validated('sort_by', 'name');
        /** @var string $sortDirection */
        $sortDirection = $request->validated('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        /** @var int $perPage */
        $perPage = $request->validated('per_page', 10);
        $companies = $query->paginate($perPage);

        /** @var array{data: array<mixed>, meta: array{current_page: int, last_page: int, per_page: int, total: int, from: int|null, to: int|null, links: array<mixed>}} $paginatedData */
        $paginatedData = CompanyResource::collection($companies)->response()->getData(true);

        return Inertia::render('dashboard', [
            'companies' => [
                'data' => $paginatedData['data'],
                'current_page' => $paginatedData['meta']['current_page'],
                'last_page' => $paginatedData['meta']['last_page'],
                'per_page' => $paginatedData['meta']['per_page'],
                'total' => $paginatedData['meta']['total'],
                'from' => $paginatedData['meta']['from'],
                'to' => $paginatedData['meta']['to'],
                'links' => $paginatedData['meta']['links'],
            ],
            'filters' => [
                'search' => $search,
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection,
                'per_page' => $perPage,
            ],
        ]);
    }
}
