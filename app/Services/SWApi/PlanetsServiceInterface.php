<?php

declare(strict_types=1);

namespace App\Services\SWApi;

use App\DTOs\SWApi\Planet;
use App\DTOs\SWApi\Response;

interface PlanetsServiceInterface
{
    /**
     * @param array<string, mixed> $query
     *
     * @return Response<Planet>
     */
    public function search(array $query = []): Response;

    public function getById(int $id): ?Planet;
}
