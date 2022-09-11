<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Cache\ByMergingParametersResolver;
use App\Services\Cache\CacheWithOptionsResolver;
use App\Services\Hashing\ByJsonEncodedHasher;
use App\Services\Hashing\Hasher;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Hasher::class, ByJsonEncodedHasher::class);
        $this->app->bind(CacheWithOptionsResolver::class, ByMergingParametersResolver::class);
    }
}
