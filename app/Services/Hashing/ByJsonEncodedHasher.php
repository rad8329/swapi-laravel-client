<?php

declare(strict_types=1);

namespace App\Services\Hashing;

use JsonException;

class ByJsonEncodedHasher implements Hasher
{
    /**
     * @param array<int|string, mixed> $array
     *
     * @throws JsonException when the query was not able to be serialized
     */
    public function fromArray(array $array): string
    {
        return sha1(json_encode($array, JSON_THROW_ON_ERROR));
    }
}
