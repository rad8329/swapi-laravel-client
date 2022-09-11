<?php

declare(strict_types=1);

namespace App\Services\Cache;

interface CacheWithOptionsResolver extends CacheResolver
{
    /**
     * @param array<int|string, mixed> $options
     */
    public function resolve(string $key, array $options = []): string;
}
