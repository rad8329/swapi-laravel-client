<?php

declare(strict_types=1);

namespace Tests\Integration\Repositories\PlanetsRestRepository;

use App\Repositories\Cacheable;
use App\Repositories\SWApi\PlanetsRepository;
use App\Repositories\SWApi\PlanetsRestRepository;
use Illuminate\Support\Facades\Cache;
use JsonException;
use Tests\TestCase;

/**
 * @covers \App\Repositories\SWApi\PlanetsRestRepository::get
 */
class GetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    /**
     * Test that SUT is working as expected when a search term is not provided.
     *
     * @throws JsonException when the query was not able to be serialized
     */
    public function testItIsGettingAsExpected(): void
    {
        $cacheable = new class() {
            use Cacheable;

            public function makeHash(array $values)
            {
                return $this->hash($values);
            }
        };
        $request = ['planet' => 1];
        $queryHash = $cacheable->makeHash($request);

        /** @var PlanetsRestRepository $repository */
        $repository = app(PlanetsRepository::class);
        $planet = $repository->get($request['planet']);

        $this->assertSame('Tatooine', $planet->name);
        $this->assertSame(200000, $planet->population);
        $this->assertCount(10, $planet->residents);

        $this->assertTrue(Cache::has($queryHash));

        $this->assertSame(
            json_encode(Cache::get($queryHash), JSON_THROW_ON_ERROR),
            json_encode($planet, JSON_THROW_ON_ERROR)
        );

        $secondResult = $repository->get(2);

        $this->assertNotSame(
            json_encode(Cache::get($queryHash), JSON_THROW_ON_ERROR),
            json_encode($secondResult, JSON_THROW_ON_ERROR)
        );
    }
}
