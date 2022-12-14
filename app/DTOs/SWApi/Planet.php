<?php

declare(strict_types=1);

namespace App\DTOs\SWApi;

use App\Helpers\SWApi\Helper;
use Illuminate\Support\Collection;

class Planet
{
    public readonly int $id;

    /**
     * @param Collection<int, string> $residents a list of URLs
     */
    public function __construct(readonly string $name,
                                readonly string $terrain,
                                readonly int $population,
                                readonly Collection $residents,
                                readonly string $url)
    {
        $this->id = Helper::extractPlanetIdFromUrl($url);
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromRawArray(array $values): self
    {
        return new self(
            $values['name'] ?? '',
            $values['terrain'] ?? '',
            (int) ($values['population'] ?? 0),
            collect((array) ($values['residents'] ?? [])),
            $values['url'] ?? ''
        );
    }
}
