<?php

declare(strict_types=1);

namespace App\DTOs\SWApi;

use App\Helpers\SWApi\Helper;
use Illuminate\Support\Collection;

class Person
{
    public readonly int $id;

    /**
     * @param Collection<int, string> $films a list of URLs
     */
    public function __construct(readonly string $name,
                                readonly string $height,
                                readonly string $mass,
                                readonly string $hairColor,
                                readonly string $skinColor,
                                readonly string $eyeColor,
                                readonly string $birthYear,
                                readonly string $gender,
                                readonly string $homeWorld,
                                readonly Collection $films,
                                readonly string $url)
    {
        $this->id = Helper::extractPeopleIdFromUrl($url);
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromRawArray(array $values): self
    {
        return new self(
            $values['name'] ?? '',
            $values['height'] ?? '',
            $values['mass'] ?? '',
            $values['hair_color'] ?? '',
            $values['skin_color'] ?? '',
            $values['eye_color'] ?? '',
            $values['birth_year'] ?? '',
            $values['gender'] ?? '',
            $values['homeworld'] ?? '',
            collect((array) ($values['films'] ?? [])),
            $values['url'] ?? ''
        );
    }
}
