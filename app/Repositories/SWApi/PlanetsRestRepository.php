<?php

declare(strict_types=1);

namespace App\Repositories\SWApi;

use App\DTOs\SWApi\Planet;
use App\DTOs\SWApi\ResultSet;
use JsonException;

class PlanetsRestRepository extends RestRepository implements PlanetsRepository
{
    /**
     * @param array<string, mixed> $query
     *
     * @throws JsonException when the query was not able to be serialized
     */
    public function search(array $query = []): ResultSet
    {
        if (!empty($query['search'])) {
            unset($query['page']);
        }

        return $this->remember($this->hash($query), function () use ($query): ResultSet {
            $clientResponse = $this->request->get($this->url . 'planets', $query);

            $results = $clientResponse->collect('results')
                ->map(fn (array $rawValue) => Planet::fromRawArray($rawValue));

            return new ResultSet($results, (int) $clientResponse->json('count'));
        });
    }

    /**
     * @throws JsonException when the query was not able to be serialized
     */
    public function get(int $id): Planet
    {
        return $this->remember($this->hash(['planet' => $id]), function () use ($id): Planet {
            $clientResponse = $this->request->get($this->url . 'planets/' . $id);

            return Planet::fromRawArray($clientResponse->json());
        });
    }
}
