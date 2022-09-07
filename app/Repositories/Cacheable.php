<?php

declare(strict_types=1);

namespace App\Repositories;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use JsonException;

/**
 * @property Date  $date
 * @property Cache $cache
 */
trait Cacheable
{
    protected function remember(string $key, Closure $callable): mixed
    {
        return $this->cache::remember(
            $key,
            $this->date::now()->addSeconds(config('swapi.cache_expiration')),
            $callable
        );
    }

    /**
     * @param array<int|string, int|string|array<mixed>> $values a serializable array
     *
     * @throws JsonException when the query was not able to be serialized
     */
    protected function hash(array $values): string
    {
        return sha1(json_encode($values, JSON_THROW_ON_ERROR));
    }
}
