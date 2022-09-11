<?php

declare(strict_types=1);

namespace App\Services\SWApi;

use App\DTOs\SWApi\Person;

class PeopleService extends RestService implements PeopleServiceInterface
{
    public function getById(int $id): ?Person
    {
        // to ensure a unique cache key name we need to combine the method name and people id
        $cacheKeyName = $this->cacheResolver->resolve(__METHOD__, [$id]);

        return $this->remember($cacheKeyName, function () use ($id): ?Person {
            $clientResponse = $this->client::get('people/' . $id);

            return $clientResponse->successful() ? Person::fromRawArray($clientResponse->json()) : null;
        });
    }
}
