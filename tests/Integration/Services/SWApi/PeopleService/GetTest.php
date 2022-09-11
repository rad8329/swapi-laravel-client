<?php

declare(strict_types=1);

namespace Tests\Integration\Services\SWApi\PeopleService;

use App\Services\Cache\CacheWithOptionsResolver;
use App\Services\SWApi\PeopleService;
use App\Services\SWApi\PeopleServiceInterface;
use Illuminate\Support\Facades\Cache;
use JsonException;
use Tests\TestCase;

/**
 * @covers \App\Services\SWApi\PeopleService::getById
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
    public function test_it_is_properly_bound(): void
    {
        $repository = app(PeopleServiceInterface::class);

        $this->assertInstanceOf(PeopleService::class, $repository);
    }

    /**
     * @throws JsonException when the query was not able to be serialized
     */
    public function test_it_is_getting_as_expected(): void
    {
        $cacheResolver = app(CacheWithOptionsResolver::class);

        $personId = 1;
        $queryHash = $cacheResolver->resolve(PeopleService::class . '::getById', [$personId]);

        /** @var PeopleServiceInterface $repository */
        $repository = app(PeopleServiceInterface::class);
        $person = $repository->getById($personId);

        $expectedName = 'Luke Skywalker';
        $expectedHeight = '172';
        $expectedGender = 'male';
        $expectedNumberOfFilms = 4;

        $this->assertSame($expectedName, $person->name);
        $this->assertSame($expectedHeight, $person->height);
        $this->assertSame($expectedGender, $person->gender);
        $this->assertCount($expectedNumberOfFilms, $person->films);

        $this->assertTrue(Cache::has($queryHash));

        $expectedPersonAsJson = json_encode(Cache::get($queryHash), JSON_THROW_ON_ERROR);

        $this->assertSame($expectedPersonAsJson, json_encode($person, JSON_THROW_ON_ERROR));

        $secondResult = $repository->getById(2);

        $this->assertNotSame($expectedPersonAsJson, json_encode($secondResult, JSON_THROW_ON_ERROR));
    }

    /**
     * Test that SUT will return null when the person was not found.
     */
    public function test_it_will_return_null_when_not_found(): void
    {
        $nonExistentPersonId = 75757575;

        /** @var PeopleServiceInterface $repository */
        $repository = app(PeopleServiceInterface::class);

        $this->assertNull($repository->getById($nonExistentPersonId));
    }
}
