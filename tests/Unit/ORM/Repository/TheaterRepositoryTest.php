<?php

declare(strict_types=1);

namespace Tests\Unit\ORM\Repository;

use App\ORM\Entity\Theater;
use App\ORM\Repository\TheaterRepository;
use Doctrine\ORM\QueryBuilder;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class TheaterRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(TheaterRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&TheaterRepository
     */
    protected function createTargetMock()
    {
        return Mockery::mock(TheaterRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&QueryBuilder
     */
    protected function createQueryBuilderMock()
    {
        return Mockery::mock(QueryBuilder::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface
     */
    protected function createQueryMock()
    {
        return Mockery::mock('Query');
    }

    /**
     * @test
     */
    public function testAddActiveQuery(): void
    {
        $alias = 'test';

        $queryBuilderMock = $this->createQueryBuilderMock();

        // parent
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($alias . '.isDeleted = false');

        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($alias . '.status IN (:status)')
            ->andReturn($queryBuilderMock);

        $statuses = [
            Theater::STATUS_OPEN,
            Theater::STATUS_CLOSED,
        ];
        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->once()
            ->with('status', $statuses);

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $addActiveQueryRef = $targetRef->getMethod('addActiveQuery');
        $addActiveQueryRef->setAccessible(true);

        $addActiveQueryRef->invoke($targetMock, $queryBuilderMock, $alias);
    }

    /**
     * @test
     */
    public function testAddFixTheaterMetaNPlasOneQuery(): void
    {
        $alias     = 'ts';
        $aliasMeta = 'tsm';

        $queryBuilderMock = $this->createQueryBuilderMock();

        $queryBuilderMock
            ->shouldReceive('select')
            ->once()
            ->with($alias . ', ' . $aliasMeta)
            ->andReturn($queryBuilderMock);

        $queryBuilderMock
            ->shouldReceive('innerJoin')
            ->once()
            ->with($alias . '.meta', $aliasMeta);

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $addActiveQueryRef = $targetRef->getMethod('addFixTheaterMetaNPlasOneQuery');
        $addActiveQueryRef->setAccessible(true);

        $addActiveQueryRef->invoke($targetMock, $queryBuilderMock, $alias, $aliasMeta);
    }

    /**
     * @test
     */
    public function testFindByActive(): void
    {
        $alias = 't';

        $queryBuilderMock = $this->createQueryBuilderMock();

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $targetMock
            ->shouldReceive('createQueryBuilder')
            ->once()
            ->with($alias)
            ->andReturn($queryBuilderMock);
        $targetMock
            ->shouldReceive('addFixTheaterMetaNPlasOneQuery')
            ->once()
            ->with($queryBuilderMock, $alias, 'tm');
        $targetMock
            ->shouldReceive('addActiveQuery')
            ->once()
            ->with($queryBuilderMock, $alias);

        $queryBuilderMock
            ->shouldReceive('orderBy')
            ->once()
            ->with($alias . '.displayOrder', 'ASC');

        $queryMock = $this->createQueryMock();
        $queryBuilderMock
            ->shouldReceive('getQuery')
            ->once()
            ->andReturn($queryMock);

        $result = [];
        $queryMock
            ->shouldReceive('getResult')
            ->once()
            ->andReturn($result);

        $this->assertEquals($result, $targetMock->findByActive());
    }

    /**
     * @test
     */
    public function testFindOneByName(): void
    {
        $alias = 't';

        $queryBuilderMock = $this->createQueryBuilderMock();

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $targetMock
            ->shouldReceive('createQueryBuilder')
            ->once()
            ->with($alias)
            ->andReturn($queryBuilderMock);
        $targetMock
            ->shouldReceive('addFixTheaterMetaNPlasOneQuery')
            ->once()
            ->with($queryBuilderMock, $alias, 'tm');
        $targetMock
            ->shouldReceive('addActiveQuery')
            ->once()
            ->with($queryBuilderMock, $alias);

        $name = 'test';
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($alias . '.name = :name')
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->once()
            ->with('name', $name);

        $queryMock = $this->createQueryMock();
        $queryBuilderMock
            ->shouldReceive('getQuery')
            ->once()
            ->andReturn($queryMock);

        $result = null;
        $queryMock
            ->shouldReceive('getOneOrNullResult')
            ->once()
            ->andReturn($result);

        $this->assertEquals($result, $targetMock->findOneByName($name));
    }

    /**
     * @test
     */
    public function testFindBySpecialSite(): void
    {
        $alias = 't';

        $queryBuilderMock = $this->createQueryBuilderMock();

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $targetMock
            ->shouldReceive('createQueryBuilder')
            ->once()
            ->with($alias)
            ->andReturn($queryBuilderMock);
        $targetMock
            ->shouldReceive('addFixTheaterMetaNPlasOneQuery')
            ->once()
            ->with($queryBuilderMock, $alias, 'tm');
        $targetMock
            ->shouldReceive('addActiveQuery')
            ->once()
            ->with($queryBuilderMock, $alias);

        $ailiasSpecialSites = 's';
        $queryBuilderMock
            ->shouldReceive('join')
            ->once()
            ->with($alias . '.specialSites', $ailiasSpecialSites)
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($ailiasSpecialSites . '.id = :special_site_id')
            ->andReturn($queryBuilderMock);

        $specialSiteId = 3;
        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->once()
            ->with('special_site_id', $specialSiteId)
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('orderBy')
            ->once()
            ->with($alias . '.displayOrder', 'ASC');

        $queryMock = $this->createQueryMock();
        $queryBuilderMock
            ->shouldReceive('getQuery')
            ->once()
            ->andReturn($queryMock);

        $result = [];
        $queryMock
            ->shouldReceive('getResult')
            ->once()
            ->andReturn($result);

        $this->assertEquals($result, $targetMock->findBySpecialSite($specialSiteId));
    }
}
