<?php

declare(strict_types=1);

namespace App\Repositories\SWApi;

use App\DTOs\SWApi\Response;

interface PlanetsRepository
{
    /**
     * @param array<string, mixed> $query
     */
    public function search(array $query = []): Response;
}
