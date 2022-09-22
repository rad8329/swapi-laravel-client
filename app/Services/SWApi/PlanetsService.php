<?php

declare(strict_types=1);

namespace App\Services\SWApi;

use App\DTOs\SWApi\Planet;
use App\DTOs\SWApi\PlanetResults;
use App\DTOs\SWApi\Response;
use Illuminate\Http\Client\ConnectionException;
use RuntimeException;
use Throwable;

class PlanetsService extends RestService implements PlanetsServiceInterface
{
    /**
     * @param array<string, mixed> $query
     *
     * @throws Throwable when the circuit is not still closed, it will rethrow the encountered exception
     *
     * @return Response<Planet>
     */
    public function search(array $query = []): Response
    {
        $query = array_filter($query);

        if (!empty($query['search'])) {
            unset($query['page']);
        }

        // to get a unique cache key name we need to combine the method name and query parameters
        $cacheKeyName = $this->cacheResolver->resolve(__METHOD__, $query);

        $pullPlanetsHandler = fn () => $this->remember($cacheKeyName, function () use ($query): Response {
            $clientResponse = $this->client::get('planets', $query);

            $results = PlanetResults::fromRawArray($clientResponse->json('results'));

            return new Response($results, (int) $clientResponse->json('count'));
        });

        return $this->try(
            circuitId: 'planets',
            whenCircuitIsOpen: $pullPlanetsHandler,
            whenCircuitIsClosed: fn () => throw new RuntimeException('The circuit breaker for planets is closed'),
            circuitStatusResolver: fn (Throwable $e) => !$e instanceof ConnectionException
        );
    }

    /**
     * @throws Throwable when the circuit is not still closed, it will rethrow the encountered exception
     */
    public function getById(int $id): ?Planet
    {
        // to get a unique cache key name we need to combine the method name and planet id
        $cacheKeyName = $this->cacheResolver->resolve(__METHOD__, [$id]);

        $pullPlanetHandler = fn () => $this->remember($cacheKeyName, function () use ($id): ?Planet {
            $clientResponse = $this->client::get('planets/' . $id);

            return $clientResponse->successful() ? Planet::fromRawArray($clientResponse->json()) : null;
        });

        return $this->try(
            circuitId: 'planet',
            whenCircuitIsOpen: $pullPlanetHandler,
            whenCircuitIsClosed: fn () => throw new RuntimeException('The circuit breaker for planet is closed'),
            circuitStatusResolver: fn (Throwable $e) => !$e instanceof ConnectionException
        );
    }
}
