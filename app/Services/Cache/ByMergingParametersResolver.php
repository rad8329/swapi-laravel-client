<?php

declare(strict_types=1);

namespace App\Services\Cache;

use App\Services\Hashing\Hasher;

class ByMergingParametersResolver implements CacheWithOptionsResolver
{
    public function __construct(private readonly Hasher $hasher)
    {
    }

    public function resolve(string $key, array $options = []): string
    {
        return $this->hasher->fromArray($options + ['_cacheKey' => $key]);
    }
}
