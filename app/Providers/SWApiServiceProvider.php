<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\SWApi\PeopleRepository;
use App\Repositories\SWApi\PeopleRestRepository;
use App\Repositories\SWApi\PlanetsRepository;
use App\Repositories\SWApi\PlanetsRestRepository;
use Illuminate\Support\ServiceProvider;

class SWApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PlanetsRepository::class, PlanetsRestRepository::class);
        $this->app->bind(PeopleRepository::class, PeopleRestRepository::class);
    }
}
