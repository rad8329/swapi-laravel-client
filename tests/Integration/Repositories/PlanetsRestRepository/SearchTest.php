<?php

declare(strict_types=1);

namespace Tests\Integration\Repositories\PlanetsRestRepository;

use App\DTOs\SWApi\Planet;
use App\Repositories\Cacheable;
use App\Repositories\SWApi\PlanetsRepository;
use App\Repositories\SWApi\PlanetsRestRepository;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * @covers \App\Repositories\SWApi\PlanetsRestRepository::search
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
    public function testItIsProperlyBound(): void
    {
        $repository = app(PlanetsRepository::class);

        $this->assertInstanceOf(PlanetsRestRepository::class, $repository);
    }

    /**
     * Test that SUT is working as expected when a search term is not provided.
     */
    public function testItIsSearchingWithoutTerms(): void
    {
        /** @var PlanetsRestRepository $repository */
        $repository = app(PlanetsRepository::class);

        $result = $repository->search();

        $this->assertEquals(60, $result->count);
        $this->assertCount(10, $result->result);

        $firstPlanet = $result->result->first();

        $this->assertInstanceOf(Planet::class, $firstPlanet);
        $this->assertSame('Tatooine', $firstPlanet->name);
        $this->assertSame(200000, $firstPlanet->population);
        $this->assertCount(10, $firstPlanet->residents);
    }

    /**
     * Test that SUT is working as expected when a search term is provided.
     */
    public function testItIsSearchingWithTerms(): void
    {
        /** @var PlanetsRestRepository $repository */
        $repository = app(PlanetsRepository::class);

        $result = $repository->search(['search' => 'no']);

        $this->assertEquals(5, $result->count);
        $this->assertCount(5, $result->result);

        $firstPlanet = $result->result->first();

        $this->assertInstanceOf(Planet::class, $firstPlanet);
        $this->assertSame('Kamino', $firstPlanet->name);
        $this->assertSame(1000000000, $firstPlanet->population);

        $this->assertCount(3, $firstPlanet->residents);
    }

    /**
     * Test that SUT is caching as expected.
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function testItIsUsingCache(): void
    {
        $cacheable = new class() {
            use Cacheable;

            public function makeHash(array $values)
            {
                return $this->hash($values);
            }
        };

        $request = ['search' => 'no'];
        $queryHash = $cacheable->makeHash($request);

        /** @var PlanetsRestRepository $repository */
        $repository = app(PlanetsRepository::class);

        $result = $repository->search($request);

        $this->assertTrue(Cache::has($queryHash));

        $this->assertSame(
            json_encode(Cache::get($queryHash), JSON_THROW_ON_ERROR),
            json_encode($result, JSON_THROW_ON_ERROR)
        );

        $secondResult = $repository->search(['search' => 'ha']);

        $this->assertNotSame(
            json_encode(Cache::get($queryHash), JSON_THROW_ON_ERROR),
            json_encode($secondResult, JSON_THROW_ON_ERROR)
        );
    }
}
