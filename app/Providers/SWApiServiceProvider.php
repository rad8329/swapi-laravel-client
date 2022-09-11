<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\SWApi\PeopleService;
use App\Services\SWApi\PeopleServiceInterface;
use App\Services\SWApi\PlanetsService;
use App\Services\SWApi\PlanetsServiceInterface;
use Illuminate\Support\ServiceProvider;

class SWApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PlanetsServiceInterface::class, PlanetsService::class);
        $this->app->bind(PeopleServiceInterface::class, PeopleService::class);
    }
}
