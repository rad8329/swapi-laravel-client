<?php

declare(strict_types=1);

namespace App\Services\CircuitBreaker\Cache;

use App\Services\CircuitBreaker\CircuitBreakerInterface;
use Closure;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Psr\Log\LoggerInterface;
use Throwable;

class CacheCircuitBreaker implements CircuitBreakerInterface
{
    public function __construct(private readonly CacheRepository $cache, private readonly LoggerInterface $logger)
    {
    }

    /** {@inheritDoc} */
    public function try(
        string $circuitId,
        Closure $whenCircuitIsOpen,
        Closure $whenCircuitIsClosed,
        Closure $circuitStatusResolver,
        int $threshold = self::THRESHOLD,
        int $timeToLiveInSeconds = self::TIME_TO_LIVE_IN_SECONDS
    ): mixed {
        $cacheCircuitId = sprintf('circuit_breaker.%s', $circuitId);

        /** @var CircuitBreaker $circuitBreaker */
        $circuitBreaker = $this->cache->get($cacheCircuitId) ?? new CircuitBreaker($threshold);

        if ($circuitBreaker->isClosed()) {
            return $whenCircuitIsClosed();
        }

        try {
            return $whenCircuitIsOpen();
        } catch (Throwable $throwable) {
            if (!$circuitStatusResolver($throwable)) {
                $isClosed = $circuitBreaker->addFailure()->isClosed();

                $this->cache->put($cacheCircuitId, $circuitBreaker, $timeToLiveInSeconds);

                if ($isClosed) {
                    $this->logger->critical(sprintf('%s has reached the threshold', $cacheCircuitId));
                }

                // circuit should not be closed
                // rethrow the exception
                throw $throwable;
            }

            // run the failure handler
            return $whenCircuitIsClosed();
        }
    }
}
