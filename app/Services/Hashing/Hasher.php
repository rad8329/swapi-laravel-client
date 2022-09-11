<?php

declare(strict_types=1);

namespace App\Services\Hashing;

interface Hasher
{
    /**
     * @param array<int|string, mixed> $array
     */
    public function fromArray(array $array): string;
}
