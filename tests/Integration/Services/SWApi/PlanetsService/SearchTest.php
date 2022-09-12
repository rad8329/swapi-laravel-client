<?php

declare(strict_types=1);

namespace Tests\Integration\Services\SWApi\PlanetsService;

use App\DTOs\SWApi\Planet;
use App\Services\SWApi\PlanetsService;
use App\Services\Cache\CacheWithOptionsResolver;
use App\Services\SWApi\PlanetsServiceInterface;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * @covers \App\Services\SWApi\PlanetsService::search
 */
class SearchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    /**
     * Test that SUT class is being bound by the service container.
     */
    public function test_it_is_properly_bound(): void
    {
        $repository = app(PlanetsServiceInterface::class);

        $this->assertInstanceOf(PlanetsService::class, $repository);
    }

    /**
     * Test that SUT is working as expected when a search term is not provided.
     */
    public function test_it_is_searching_without_terms(): void
    {
        /** @var PlanetsServiceInterface $repository */
        $repository = app(PlanetsServiceInterface::class);

        $result = $repository->search();

        $this->assertEquals(60, $result->count);
        $this->assertCount(10, $result->results);

        /** @var Planet $firstPlanet */
        $firstPlanet = $result->results->first();

        $expectedName = 'Tatooine';
        $expectedPopulation = 200000;
        $expectedNumberOfResidents = 10;

        $this->assertInstanceOf(Planet::class, $firstPlanet);
        $this->assertSame($expectedName, $firstPlanet->name);
        $this->assertSame($expectedPopulation, $firstPlanet->population);
        $this->assertCount($expectedNumberOfResidents, $firstPlanet->residents);
    }

    /**
     * Test that SUT is working as expected when a search term is provided.
     */
    public function test_it_is_searching_with_terms(): void
    {
        /** @var PlanetsServiceInterface $repository */
        $repository = app(PlanetsServiceInterface::class);

        $result = $repository->search(['search' => 'no']);

        $this->assertEquals(5, $result->count);
        $this->assertCount(5, $result->results);

        /** @var Planet $firstPlanet */
        $firstPlanet = $result->results->first();

        $expectedName = 'Kamino';
        $expectedPopulation = 1000000000;
        $expectedNumberOfResidents = 3;

        $this->assertInstanceOf(Planet::class, $firstPlanet);
        $this->assertSame($expectedName, $firstPlanet->name);
        $this->assertSame($expectedPopulation, $firstPlanet->population);
        $this->assertCount($expectedNumberOfResidents, $firstPlanet->residents);
    }

    /**
     * Test that SUT is caching as expected.
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function test_it_is_using_cache(): void
    {
        $cacheResolver = app(CacheWithOptionsResolver::class);

        $request = ['search' => 'no'];
        $queryHash = $cacheResolver->resolve(PlanetsService::class . '::search', $request);

        /** @var PlanetsServiceInterface $repository */
        $repository = app(PlanetsServiceInterface::class);

        $result = $repository->search($request);

        $this->assertTrue(Cache::has($queryHash));

        $expectedPlanetAsJson = json_encode(Cache::get($queryHash), JSON_THROW_ON_ERROR);

        $this->assertSame($expectedPlanetAsJson, json_encode($result, JSON_THROW_ON_ERROR));

        $secondResult = $repository->search(['search' => 'ha']);

        $this->assertNotSame($expectedPlanetAsJson, json_encode($secondResult, JSON_THROW_ON_ERROR));
    }
}
