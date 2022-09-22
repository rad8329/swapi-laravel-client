<?php

declare(strict_types=1);

namespace App\Services\SWApi;

use App\DTOs\SWApi\Person;
use Illuminate\Http\Client\ConnectionException;
use RuntimeException;
use Throwable;

class PeopleService extends RestService implements PeopleServiceInterface
{
    /**
     * @throws Throwable when the circuit is not still closed, it will rethrow the encountered exception
     */
    public function getById(int $id): ?Person
    {
        // to ensure a unique cache key name we need to combine the method name and people id
        $cacheKeyName = $this->cacheResolver->resolve(__METHOD__, [$id]);

        $pullPersonHandler = fn () => $this->remember($cacheKeyName, function () use ($id): ?Person {
            $clientResponse = $this->client::get('people/' . $id);

            return $clientResponse->successful() ? Person::fromRawArray($clientResponse->json()) : null;
        });

        return $this->try(
            circuitId: 'person',
            whenCircuitIsOpen: $pullPersonHandler,
            whenCircuitIsClosed: fn () => throw new RuntimeException('The circuit breaker for person is closed'),
            circuitStatusResolver: fn (Throwable $e) => !$e instanceof ConnectionException
        );
    }
}
