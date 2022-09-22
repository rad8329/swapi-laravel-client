<?php

declare(strict_types=1);

namespace App\Services\SWApi;

use App\Http\Clients\SWApi\Client;
use App\Services\Cache\CacheWithOptionsResolver;
use App\Services\CircuitBreaker\CircuitBreakerInterface;
use Closure;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Date;
use Psr\Log\LoggerInterface;
use Throwable;

class RestService extends \App\Services\RestService
{
    public function __construct(Client $client,
                                CircuitBreakerInterface $circuitBreaker,
                                protected readonly CacheRepository $cache,
                                protected readonly Date $date,
                                protected readonly CacheWithOptionsResolver $cacheResolver,
                                protected readonly LoggerInterface $logger)
    {
        parent::__construct($client, $circuitBreaker);
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     */
    protected function remember(string $key, Closure $callable): mixed
    {
        return $this->cache->remember(
            $key,
            $this->date::now()->addSeconds(config('swapi.cache_expiration')),
            $callable
        );
    }

    /**
     * Try to execute a remote procedure.
     *
     * @param non-empty-string        $circuitId
     * @param Closure():mixed         $whenCircuitIsOpen
     * @param Closure():mixed         $whenCircuitIsClosed
     * @param Closure(Throwable):bool $circuitStatusResolver
     * @param int|null                $threshold             the maximum number of failures allowed before it is closed
     *
     * @throws Throwable when the circuit is not still closed, it will rethrow the encountered exception
     */
    protected function try(
        string $circuitId,
        Closure $whenCircuitIsOpen,
        Closure $whenCircuitIsClosed,
        Closure $circuitStatusResolver,
        ?int $threshold = null,
        ?int $timeToLiveInSeconds = null
    ): mixed {
        return $this->circuitBreaker->try(
            circuitId: $circuitId,
            whenCircuitIsOpen: $whenCircuitIsOpen,
            whenCircuitIsClosed: $whenCircuitIsClosed,
            circuitStatusResolver: $circuitStatusResolver,
            threshold: $threshold ?? (int) config('swapi.circuit_beaker.threshold'),
            timeToLiveInSeconds: $timeToLiveInSeconds ?? (int) config('swapi.circuit_beaker.time_to_live_in_seconds')
        );
    }
}
