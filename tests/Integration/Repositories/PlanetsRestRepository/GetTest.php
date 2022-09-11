<?php

declare(strict_types=1);

namespace Tests\Integration\Repositories\PlanetsRestRepository;

use App\Services\Cache\CacheWithOptionsResolver;
use App\Services\SWApi\PlanetsService;
use App\Services\SWApi\PlanetsServiceInterface;
use Illuminate\Support\Facades\Cache;
use JsonException;
use Tests\TestCase;

/**
 * @covers \App\Services\SWApi\PlanetsService::getById
 */
class GetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    /**
     * @throws JsonException when the query was not able to be serialized
     */
    public function test_it_is_getting_as_expected(): void
    {
        $cacheResolver = app(CacheWithOptionsResolver::class);

        $planetId = 1;
        $queryHash = $cacheResolver->resolve(PlanetsService::class . '::getById', [$planetId]);

        /** @var PlanetsServiceInterface $repository */
        $repository = app(PlanetsServiceInterface::class);
        $planet = $repository->getById($planetId);

        $expectedName = 'Tatooine';
        $expectedPopulation = 200000;
        $expectedNumberOfResidents = 10;

        $this->assertSame($expectedName, $planet->name);
        $this->assertSame($expectedPopulation, $planet->population);
        $this->assertCount($expectedNumberOfResidents, $planet->residents);

        $this->assertTrue(Cache::has($queryHash));

        $expectedPlanetAsJson = json_encode(Cache::get($queryHash), JSON_THROW_ON_ERROR);

        $this->assertSame($expectedPlanetAsJson, json_encode($planet, JSON_THROW_ON_ERROR));

        $secondPlanet = $repository->getById(2);

        $this->assertNotSame($expectedPlanetAsJson, json_encode($secondPlanet, JSON_THROW_ON_ERROR));
    }

    /**
     * Test that SUT will return null when the planet was not found.
     */
    public function test_it_will_return_null_when_not_found(): void
    {
        $nonExistentPlanetId = 75757575;

        /** @var PlanetsServiceInterface $repository */
        $repository = app(PlanetsServiceInterface::class);

        $this->assertNull($repository->getById($nonExistentPlanetId));
    }
}
