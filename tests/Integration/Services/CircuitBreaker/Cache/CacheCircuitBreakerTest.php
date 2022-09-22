<?php

declare(strict_types=1);

namespace Tests\Integration\Services\CircuitBreaker\Cache;

use App\Services\CircuitBreaker\Cache\CacheCircuitBreaker;
use App\Services\CircuitBreaker\CircuitBreakerInterface;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\ConnectionException;
use Mockery;
use Illuminate\Support\Facades\Cache;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;
use Tests\TestCase;
use RuntimeException;
use Throwable;

/**
 * @covers \App\Services\CircuitBreaker\Cache\CacheCircuitBreaker::try
 */
class CacheCircuitBreakerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    /**
     * Test that SUT class is being bound by the service container.
     */
    public function test_it_is_properly_bound(): void
    {
        $circuitBreaker = app(CircuitBreakerInterface::class);

        $this->assertInstanceOf(CacheCircuitBreaker::class, $circuitBreaker);
    }


    /**
     * Test that SUT is working as expected when a the circuit breaker is open.
     *
     * @throws Throwable when the circuit is closed
     */
    public function test_it_will_execute_open_handler_when_it_is_open(): void
    {
        $circuitBreaker = app(CircuitBreakerInterface::class);

        $result = $circuitBreaker->try(
            circuitId: 'planets',
            whenCircuitIsOpen: fn() => ['success' => true],
            whenCircuitIsClosed: fn() => throw new RuntimeException('The circuit breaker is closed'),
            circuitStatusResolver: fn(Throwable $e) => $e instanceof ConnectionException
        );

        $this->assertSame(['success' => true], $result);
    }

    /**
     * Test that SUT is working as expected when the threshold is reached
     *
     * @throws Throwable when the circuit is closed
     */
    public function test_it_will_close_when_threshold_is_reached(): void
    {
        $circuitBreaker = app(CircuitBreakerInterface::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The circuit breaker is closed');

        $threshold = $this->faker->numberBetween(3, 8);

        for ($attempt = 0; $attempt <= $threshold; $attempt++) {
            try {
                $circuitBreaker->try(
                    circuitId: 'planets',
                    whenCircuitIsOpen: fn() => throw new ConnectionException('Some communication has failed'),
                    whenCircuitIsClosed: fn() => throw new RuntimeException('The circuit breaker is closed'),
                    circuitStatusResolver: fn(Throwable $e) => !$e instanceof ConnectionException,
                    threshold: $threshold
                );
            } catch (ConnectionException $e) {
                // nothing to do
            }
        }
    }

    /**
     * Test that SUT is working as expected when while cache is live
     *
     * @throws Throwable when the circuit is closed
     */
    public function test_it_will_remain_closed_during_time_to_live(): void
    {
        $circuitBreaker = app(CircuitBreakerInterface::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The circuit breaker is still closed');

        $threshold = $this->faker->numberBetween(3, 8);

        $timeToLive = 3;

        for ($attempt = 0; $attempt <= $threshold; $attempt++) {
            try {
                $circuitBreaker->try(
                    circuitId: 'planets',
                    whenCircuitIsOpen: fn() => throw new ConnectionException('Some communication has failed'),
                    whenCircuitIsClosed: fn() => throw new RuntimeException('The circuit breaker is closed'),
                    circuitStatusResolver: fn(Throwable $e) => !$e instanceof ConnectionException,
                    threshold: $threshold,
                    timeToLiveInSeconds: $timeToLive
                );
            } catch (ConnectionException|RuntimeException $e) {
                // nothing to do
            }
        }

        sleep($timeToLive - 1); // simulate a retry after some seconds

        $circuitBreaker->try(
            circuitId: 'planets',
            whenCircuitIsOpen: fn() => ['success' => true],
            whenCircuitIsClosed: fn() => throw new RuntimeException('The circuit breaker is still closed'),
            circuitStatusResolver: fn(Throwable $e) => !$e instanceof ConnectionException,
            threshold: $threshold,
            timeToLiveInSeconds: 5
        );
    }

    /**
     * Test that SUT will log a critical record when it reaches the threshold
     *
     * @throws Throwable when the circuit is closed
     */
    public function test_it_will_log_a_critical_record(): void
    {
        $mockedLogger = $this->instance(
            LoggerInterface::class,
            Mockery::mock(LoggerInterface::class, static function (MockInterface $mock) {
                $mock->expects('critical')->once()->with('circuit_breaker.planets has reached the threshold');
            })
        );

        $circuitBreaker = app(CircuitBreakerInterface::class, [app(Repository::class), $mockedLogger]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The circuit breaker is still closed');

        $threshold = $this->faker->numberBetween(3, 8);

        $timeToLive = 3;

        for ($attempt = 0; $attempt <= $threshold; $attempt++) {
            try {
                $circuitBreaker->try(
                    circuitId: 'planets',
                    whenCircuitIsOpen: fn() => throw new ConnectionException('Some communication has failed'),
                    whenCircuitIsClosed: fn() => throw new RuntimeException('The circuit breaker is closed'),
                    circuitStatusResolver: fn(Throwable $e) => !$e instanceof ConnectionException,
                    threshold: $threshold,
                    timeToLiveInSeconds: $timeToLive
                );
            } catch (ConnectionException|RuntimeException $e) {
                // nothing to do
            }
        }

        sleep($timeToLive - 1); // simulate a retry after some seconds

        $circuitBreaker->try(
            circuitId: 'planets',
            whenCircuitIsOpen: fn() => ['success' => true],
            whenCircuitIsClosed: fn() => throw new RuntimeException('The circuit breaker is still closed'),
            circuitStatusResolver: fn(Throwable $e) => !$e instanceof ConnectionException,
            threshold: $threshold,
            timeToLiveInSeconds: 5
        );
    }

    /**
     * Test that SUT will re-open after the time to live has ended
     *
     * @throws Throwable when the circuit is closed
     */
    public function test_it_will_open_once_time_to_live_reached(): void
    {
        $circuitBreaker = app(CircuitBreakerInterface::class);

        $threshold = $this->faker->numberBetween(3, 8);

        $timeToLive = 3;

        for ($attempt = 0; $attempt <= $threshold; $attempt++) {
            try {
                $circuitBreaker->try(
                    circuitId: 'planets',
                    whenCircuitIsOpen: fn() => throw new ConnectionException('Some communication has failed'),
                    whenCircuitIsClosed: fn() => throw new RuntimeException('The circuit breaker is closed'),
                    circuitStatusResolver: fn(Throwable $e) => !$e instanceof ConnectionException,
                    threshold: $threshold,
                    timeToLiveInSeconds: $timeToLive
                );
            } catch (ConnectionException|RuntimeException $e) {
                // nothing to do
            }
        }

        sleep($timeToLive + 1); // simulate a retry after some seconds

        $result = $circuitBreaker->try(
            circuitId: 'planets',
            whenCircuitIsOpen: fn() => ['success' => true],
            whenCircuitIsClosed: fn() => throw new RuntimeException('The circuit breaker is still closed'),
            circuitStatusResolver: fn(Throwable $e) => !$e instanceof ConnectionException,
            threshold: $threshold,
            timeToLiveInSeconds: $timeToLive
        );

        $this->assertSame(['success' => true], $result);
    }
}
