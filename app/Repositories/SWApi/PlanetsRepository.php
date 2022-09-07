<?php

declare(strict_types=1);

namespace App\Repositories\SWApi;

use App\DTOs\SWApi\Planet;
use App\DTOs\SWApi\ResultSet;

interface PlanetsRepository
{
    /**
     * @param array<string, mixed> $query
     */
    public function search(array $query = []): ResultSet;

    public function get(int $id): Planet;
}
