<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Company
 */
final class CompanyResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Company $company */
        $company = $this->resource;

        return [
            'id' => $company->id,
            'name' => $company->name,
            'address' => $company->address,
            'city' => $company->city,
            'state' => $company->state->value,
            'zip' => $company->zip,
            'phone' => $company->phone,
            'status' => $company->status->value,
            'rating' => $company->rating->value,
            'type' => $company->type->value,
            'created_at' => $company->created_at->toIso8601String(),
        ];
    }
}
