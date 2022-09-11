<?php

declare(strict_types=1);

namespace App\Services\SWApi;

use App\DTOs\SWApi\Planet;
use App\DTOs\SWApi\Response;
use App\DTOs\SWApi\Results;

class PlanetsService extends RestService implements PlanetsServiceInterface
{
    /**
     * @param array<string, mixed> $query
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

        return $this->remember($cacheKeyName, function () use ($query): Response {
            $clientResponse = $this->client::get('planets', $query);

            $results = Results::make($clientResponse->json('results'))
                ->map(fn (array $rawValue) => Planet::fromRawArray($rawValue));

            return new Response($results, (int) $clientResponse->json('count'));
        });
    }

    public function getById(int $id): ?Planet
    {
        // to get a unique cache key name we need to combine the method name and planet id
        $cacheKeyName = $this->cacheResolver->resolve(__METHOD__, [$id]);

        return $this->remember($cacheKeyName, function () use ($id): ?Planet {
            $clientResponse = $this->client::get('planets/' . $id);

            return $clientResponse->successful() ? Planet::fromRawArray($clientResponse->json()) : null;
        });
    }
}
