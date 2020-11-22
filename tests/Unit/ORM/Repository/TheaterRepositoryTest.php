<?php

declare(strict_types=1);

namespace Tests\Unit\ORM\Repository;

use App\ORM\Entity\Theater;
use App\ORM\Repository\TheaterRepository;
use Doctrine\ORM\QueryBuilder;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

final class TheaterRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(TheaterRepository::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&TheaterRepository
     */
    protected function createTargetMock()
    {
        return Mockery::mock(TheaterRepository::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&QueryBuilder
     */
    protected function createQueryBuilderMock()
    {
        return Mockery::mock(QueryBuilder::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface
     */
    protected function createQueryMock()
    {
        return Mockery::mock('Query');
    }

    /**
     * @test
     * @return void
     */
    public function testAddActiveQuery()
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
     * @return void
     */
    public function testFindByActive()
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
     * @return void
     */
    public function testFindOneByName()
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
     * @return void
     */
    public function testFindBySpecialSite()
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
