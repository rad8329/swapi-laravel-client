<?php

declare(strict_types=1);

namespace App\Repositories\SWApi;

use App\DTOs\SWApi\Planet;
use App\DTOs\SWApi\Response;
use App\Repositories\RestRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use JsonException;

class PlanetsRestRepository extends RestRepository implements PlanetsRepository
{
    private string $url;

    public function __construct(Http $client, Cache $cache, Date $date)
    {
        $this->url = config('swapi.url');
        $client::timeout(config('swapi.timeout'));

        parent::__construct($client, $cache, $date);
    }

    /**
     * @param array<string, mixed> $query
     *
     * @throws JsonException when the query was not able to be serialized
     */
    public function search(array $query = []): Response
    {
        if (!empty($query['search'])) {
            unset($query['page']);
        }

        return $this->remember($this->hash($query), function () use ($query): Response {
            $clientResponse = $this->client::get(
                $this->url . 'planets',
                $query
            );

            $results = $clientResponse->collect('results')
                ->map(fn (array $rawValue) => Planet::fromRawArray($rawValue));

            return new Response($results, (int) $clientResponse->json('count'));
        });
    }
}
