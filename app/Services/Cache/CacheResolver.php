<?php

declare(strict_types=1);

namespace App\Services\Cache;

interface CacheResolver
{
    public function resolve(string $key): string;
}
