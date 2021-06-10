<?php

declare(strict_types=1);

namespace Tests\Unit\ORM\Repository;

use App\ORM\Entity\News;
use App\ORM\Repository\NewsRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
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
     * @return MockInterface&LegacyMockInterface&News
     */
    protected function createNewsMock()
    {
        return Mockery::mock(News::class);
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

    /**
     * @covers ::findByTitleId
     * @test
     * @testdox findByTitleIdは引数titleIdにマッチするエンティティNewsのリストを取得する
     */
    public function testFindByTitleId(): void
    {
        $titleId = 5;

        $result = [$this->createNewsMock()];

        $newsRepositoryMock = $this->createNewsRepositoryMock();
        $newsRepositoryMock->makePartial();

        $queryBuilderMock = $this->baseTestFindByTitleId($newsRepositoryMock, $titleId, $result);

        // without limit
        $queryBuilderMock->shouldNotReceive('setMaxResults');

        $this->assertEquals(
            $result,
            $newsRepositoryMock->findByTitleId($titleId)
        );
    }

    /**
     * @covers ::findByTitleId
     * @test
     * @testdox findByTitleIdは引数titleIdにマッチするエンティティNewsのリストを引数limitの件数取得する
     */
    public function testFindByTitleIdCaseWithLimit(): void
    {
        $titleId = 5;
        $limit   = 10;

        $result = [$this->createNewsMock()];

        $newsRepositoryMock = $this->createNewsRepositoryMock();
        $newsRepositoryMock->makePartial();

        $queryBuilderMock = $this->baseTestFindByTitleId($newsRepositoryMock, $titleId, $result);

        $queryBuilderMock
            ->shouldReceive('setMaxResults')
            ->once()
            ->with($limit);

        $this->assertEquals(
            $result,
            $newsRepositoryMock->findByTitleId($titleId, $limit)
        );
    }

    /**
     * @param mixed  $newsRepositoryMock
     * @param News[] $result
     * @return MockInterface&LegacyMockInterface&QueryBuilder
     */
    protected function baseTestFindByTitleId($newsRepositoryMock, int $titleId, array $result)
    {
        $alias = 'n';

        $newsRepositoryMock->shouldAllowMockingProtectedMethods();

        $queryBuilderMock = $this->createQueryBuilderMock();
        $newsRepositoryMock
            ->shouldReceive('createQueryBuilder')
            ->once()
            ->with($alias)
            ->andReturn($queryBuilderMock);

        $newsRepositoryMock
            ->shouldReceive('addActiveQuery')
            ->once()
            ->with($queryBuilderMock, $alias);

        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($alias . '.title = :title')
            ->andReturn($queryBuilderMock);

        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->once()
            ->with('title', $titleId)
            ->andReturn($queryBuilderMock);

        /** @var MockInterface&LegacyMockInterface $queryMock */
        $queryMock = Mockery::mock('Query');
        $queryBuilderMock
            ->shouldReceive('getQuery')
            ->once()
            ->with()
            ->andReturn($queryMock);

        $queryMock
            ->shouldReceive('setFetchMode')
            ->once()
            ->with(News::class, 'image', ClassMetadata::FETCH_EAGER);

        $queryMock
            ->shouldReceive('getResult')
            ->once()
            ->with()
            ->andReturn($result);

        return $queryBuilderMock;
    }
}
