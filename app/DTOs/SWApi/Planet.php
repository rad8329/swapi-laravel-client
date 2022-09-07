<?php

declare(strict_types=1);

namespace App\DTOs\SWApi;

use Illuminate\Support\Collection;

class Planet
{
    /**
     * @param Collection<int, string> $residents a list of URLs
     */
    public function __construct(readonly string $name,
                                readonly string $terrain,
                                readonly int $population,
                                readonly Collection $residents)
    {
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromRawArray(array $values): Planet
    {
        return new Planet(
            $values['name'] ?? '',
            $values['terrain'] ?? '',
            (int) ($values['population'] ?? 0),
            collect((array) ($values['residents'] ?? []))
        );
    }
}
