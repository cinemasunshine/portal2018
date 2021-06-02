<?php

declare(strict_types=1);

namespace Tests\Unit\ORM\Repository;

use App\ORM\Repository\NewsRepository;
use Doctrine\ORM\QueryBuilder;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @coversDefaultClass \App\ORM\Repository\NewsRepository
 */
final class NewsRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface&LegacyMockInterface&NewsRepository
     */
    protected function createNewsRepositoryMock()
    {
        return Mockery::mock(NewsRepository::class);
    }

    protected function createNewsRepositoryReflection(): ReflectionClass
    {
        return new ReflectionClass(NewsRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&QueryBuilder
     */
    protected function createQueryBuilderMock()
    {
        return Mockery::mock(QueryBuilder::class);
    }

    /**
     * @covers ::addActiveQuery
     * @test
     * @testdox addActiveQueryは有効なデータを取得するためのクエリを追加する
     */
    public function testAddActiveQuery(): void
    {
        $alias = 'test';

        $queryBuilderMock = $this->createQueryBuilderMock();
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($alias . '.isDeleted = false')
            ->andReturn($queryBuilderMock);

        $startDtLte = Mockery::mock('Expr\Comparison');
        $queryBuilderMock
            ->shouldReceive('expr->lte')
            ->once()
            ->with($alias . '.startDt', 'CURRENT_TIMESTAMP()')
            ->andReturn($startDtLte);

        $endDtGt = Mockery::mock('Expr\Comparison');
        $queryBuilderMock
            ->shouldReceive('expr->gt')
            ->once()
            ->with($alias . '.endDt', 'CURRENT_TIMESTAMP()')
            ->andReturn($endDtGt);

        $andX = Mockery::mock('Expr\Andx');
        $queryBuilderMock
            ->shouldReceive('expr->andX')
            ->once()
            ->with($startDtLte, $endDtGt)
            ->andReturn($andX);

        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($andX)
            ->andReturn($queryBuilderMock);

        $newsRepositoryRef = $this->createNewsRepositoryReflection();

        $addActiveQueryRef = $newsRepositoryRef->getMethod('addActiveQuery');
        $addActiveQueryRef->setAccessible(true);

        $newsRepositoryMock = $this->createNewsRepositoryMock();
        $addActiveQueryRef->invoke($newsRepositoryMock, $queryBuilderMock, $alias);
    }
}
