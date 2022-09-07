<?php

declare(strict_types=1);

namespace App\Repositories\SWApi;

use App\DTOs\SWApi\Person;
use JsonException;

class PeopleRestRepository extends RestRepository implements PeopleRepository
{
    /**
     * @throws JsonException when the query was not able to be serialized
     */
    public function get(int $id): Person
    {
        return $this->remember($this->hash(['people' => $id]), function () use ($id): Person {
            $clientResponse = $this->request->get($this->url . 'people/' . $id);

            return Person::fromRawArray($clientResponse->json());
        });
    }
}
