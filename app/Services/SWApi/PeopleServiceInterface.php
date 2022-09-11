<?php

declare(strict_types=1);

namespace App\Services\SWApi;

use App\DTOs\SWApi\Person;

interface PeopleServiceInterface
{
    public function getById(int $id): ?Person;
}
