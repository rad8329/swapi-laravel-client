<?php

declare(strict_types=1);

namespace App\DTOs\SWApi;

use Illuminate\Support\Arr;

/**
 * @extends Results<Planet>
 */
class PlanetResults extends Results
{
    /**
     * @param array<int|string, mixed> $items
     */
    public static function fromRawArray(array $items): self
    {
        return new self(Arr::map(
            $items,
            static fn (array $rawValue): Planet => Planet::fromRawArray($rawValue)
        ));
    }
}
