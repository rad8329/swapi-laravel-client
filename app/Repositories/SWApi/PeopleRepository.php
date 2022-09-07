<?php

declare(strict_types=1);

namespace App\Repositories\SWApi;

use App\DTOs\SWApi\Person;

interface PeopleRepository
{
    public function get(int $id): Person;
}
