<?php

declare(strict_types=1);

namespace App\Services\CircuitBreaker;

use Closure;
use Throwable;

interface CircuitBreakerInterface
{
    public const THRESHOLD = 4;

    public const TIME_TO_LIVE_IN_SECONDS = 60;

    /**
     * @param non-empty-string        $circuitId
     * @param Closure():mixed         $whenCircuitIsOpen
     * @param Closure():mixed         $whenCircuitIsClosed
     * @param Closure(Throwable):bool $circuitStatusResolver
     * @param positive-int            $threshold             the maximum number of failures allowed before it is closed
     * @param positive-int            $timeToLiveInSeconds
     *
     * @throws Throwable when the circuit is not still closed, it will rethrow the encountered exception
     */
    public function try(
        string $circuitId,
        Closure $whenCircuitIsOpen,
        Closure $whenCircuitIsClosed,
        Closure $circuitStatusResolver,
        int $threshold,
        int $timeToLiveInSeconds
    ): mixed;
}
