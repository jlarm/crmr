<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Rating;
use App\Enums\State;
use App\Enums\Status;
use App\Enums\Type;
use Carbon\CarbonInterface;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $address
 * @property-read string $city
 * @property-read State $state
 * @property-read string $zip
 * @property-read string $phone
 * @property-read Status $status
 * @property-read Rating $rating
 * @property-read Type $type
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'address' => 'string',
            'city' => 'string',
            'state' => State::class,
            'zip' => 'string',
            'phone' => 'string',
            'status' => Status::class,
            'rating' => Rating::class,
            'type' => Type::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
