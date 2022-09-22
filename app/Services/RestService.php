<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\CircuitBreaker\CircuitBreakerInterface;
use Closure;
use Illuminate\Support\Facades\Http;

abstract class RestService
{
    public function __construct(protected readonly Http $client,
                                protected readonly CircuitBreakerInterface $circuitBreaker)
    {
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     */
    abstract protected function remember(string $key, Closure $callable): mixed;
}
