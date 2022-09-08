<?php

declare(strict_types=1);

namespace Tests\Integration\Repositories\PeopleRestRepository;

use App\Repositories\Cacheable;
use App\Repositories\SWApi\PeopleRepository;
use App\Repositories\SWApi\PeopleRestRepository;
use Illuminate\Support\Facades\Cache;
use JsonException;
use Tests\TestCase;

/**
 * @covers \App\Repositories\SWApi\PeopleRestRepository::get
 */
class GetTest extends TestCase
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
        $repository = app(PeopleRepository::class);

        $this->assertInstanceOf(PeopleRestRepository::class, $repository);
    }

    /**
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
        $request = ['people' => 1];
        $queryHash = $cacheable->makeHash($request);

        /** @var PeopleRestRepository $repository */
        $repository = app(PeopleRepository::class);
        $person = $repository->get($request['people']);

        $this->assertSame('Luke Skywalker', $person->name);
        $this->assertSame('172', $person->height);
        $this->assertSame('male', $person->gender);
        $this->assertCount(4, $person->films);

        $this->assertTrue(Cache::has($queryHash));

        $this->assertSame(
            json_encode(Cache::get($queryHash), JSON_THROW_ON_ERROR),
            json_encode($person, JSON_THROW_ON_ERROR)
        );

        $secondResult = $repository->get(2);

        $this->assertNotSame(
            json_encode(Cache::get($queryHash), JSON_THROW_ON_ERROR),
            json_encode($secondResult, JSON_THROW_ON_ERROR)
        );
    }
}
