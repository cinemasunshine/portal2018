<?php

declare(strict_types=1);

namespace Tests\Unit\ORM\Repository;

use App\ORM\Entity\Campaign;
use App\ORM\Repository\CampaignRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class CampaignRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(CampaignRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&CampaignRepository
     */
    protected function createTargetMock()
    {
        return Mockery::mock(CampaignRepository::class);
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

        $startDtLteMock = Mockery::mock('Comparison');
        $queryBuilderMock
            ->shouldReceive('expr->lte')
            ->once()
            ->with($alias . '.startDt', 'CURRENT_TIMESTAMP()')
            ->andReturn($startDtLteMock);

        $endDtGtMock = Mockery::mock('Comparison');
        $queryBuilderMock
            ->shouldReceive('expr->gt')
            ->once()
            ->with($alias . '.endDt', 'CURRENT_TIMESTAMP()')
            ->andReturn($endDtGtMock);

        $andxMock = Mockery::mock('Andx');
        $queryBuilderMock
            ->shouldReceive('expr->andX')
            ->once()
            ->with($startDtLteMock, $endDtGtMock)
            ->andReturn($andxMock);

        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($andxMock)
            ->andReturn($queryBuilderMock);

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        $addActiveQueryRef = $targetRef->getMethod('addActiveQuery');
        $addActiveQueryRef->setAccessible(true);

        $addActiveQueryRef->invoke($targetMock, $queryBuilderMock, $alias);
    }

    /**
     * @test
     */
    public function testFindByPage(): void
    {
        $pageId = 99;
        $alias  = 'c';

        $queryBuilderMock = $this->createQueryBuilderMock();

        $campaingPagesAlias = 'cp';
        $queryBuilderMock
            ->shouldReceive('join')
            ->once()
            ->with($alias . '.pages', $campaingPagesAlias)
            ->andReturn($queryBuilderMock);

        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($campaingPagesAlias . '.page = :page_id')
            ->andReturn($queryBuilderMock);

        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->once()
            ->with('page_id', $pageId)
            ->andReturn($queryBuilderMock);

        $queryBuilderMock
            ->shouldReceive('orderBy')
            ->once()
            ->with($campaingPagesAlias . '.displayOrder', 'ASC')
            ->andReturn($queryBuilderMock);

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

        $queryMock = $this->createQueryMock();
        $queryMock
            ->shouldReceive('setFetchMode')
            ->once()
            ->with(Campaign::class, 'image', ClassMetadata::FETCH_EAGER);

        $result = [];
        $queryMock
            ->shouldReceive('getResult')
            ->once()
            ->andReturn($result);

        $queryBuilderMock
            ->shouldReceive('getQuery')
            ->once()
            ->andReturn($queryMock);

        $this->assertEquals($result, $targetMock->findByPage($pageId));
    }

    /**
     * @test
     */
    public function testFindByTheater(): void
    {
        $theaterId = 99;
        $alias     = 'c';

        $queryBuilderMock = $this->createQueryBuilderMock();

        $campaingTheatersAlias = 'ct';
        $queryBuilderMock
            ->shouldReceive('join')
            ->once()
            ->with($alias . '.theaters', $campaingTheatersAlias)
            ->andReturn($queryBuilderMock);

        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($campaingTheatersAlias . '.theater = :theater_id')
            ->andReturn($queryBuilderMock);

        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->once()
            ->with('theater_id', $theaterId)
            ->andReturn($queryBuilderMock);

        $queryBuilderMock
            ->shouldReceive('orderBy')
            ->once()
            ->with($campaingTheatersAlias . '.displayOrder', 'ASC')
            ->andReturn($queryBuilderMock);

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

        $queryMock = $this->createQueryMock();
        $queryMock
            ->shouldReceive('setFetchMode')
            ->once()
            ->with(Campaign::class, 'image', ClassMetadata::FETCH_EAGER);

        $result = [];
        $queryMock
            ->shouldReceive('getResult')
            ->once()
            ->andReturn($result);

        $queryBuilderMock
            ->shouldReceive('getQuery')
            ->once()
            ->andReturn($queryMock);

        $this->assertEquals($result, $targetMock->findByTheater($theaterId));
    }

    /**
     * @test
     */
    public function testFindBySpecialSite(): void
    {
        $specialSiteId = 99;
        $alias         = 'c';

        $queryBuilderMock = $this->createQueryBuilderMock();

        $campaingSpecialSitesAlias = 'cs';
        $queryBuilderMock
            ->shouldReceive('join')
            ->once()
            ->with($alias . '.specialSites', $campaingSpecialSitesAlias)
            ->andReturn($queryBuilderMock);

        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($campaingSpecialSitesAlias . '.specialSite = :special_site_id')
            ->andReturn($queryBuilderMock);

        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->once()
            ->with('special_site_id', $specialSiteId)
            ->andReturn($queryBuilderMock);

        $queryBuilderMock
            ->shouldReceive('orderBy')
            ->once()
            ->with($campaingSpecialSitesAlias . '.displayOrder', 'ASC')
            ->andReturn($queryBuilderMock);

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

        $queryMock = $this->createQueryMock();
        $queryMock
            ->shouldReceive('setFetchMode')
            ->once()
            ->with(Campaign::class, 'image', ClassMetadata::FETCH_EAGER);

        $result = [];
        $queryMock
            ->shouldReceive('getResult')
            ->once()
            ->andReturn($result);

        $queryBuilderMock
            ->shouldReceive('getQuery')
            ->once()
            ->andReturn($queryMock);

        $this->assertEquals($result, $targetMock->findBySpecialSite($specialSiteId));
    }
}
