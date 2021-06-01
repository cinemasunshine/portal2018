<?php

declare(strict_types=1);

namespace Tests\Unit\ORM\Repository;

use App\ORM\Entity\Schedule;
use App\ORM\Repository\ScheduleRepository;
use Doctrine\ORM\QueryBuilder;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class ScheduleRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface&LegacyMockInterface&ScheduleRepository
     */
    protected function createScheduleRepositoryMock()
    {
        return Mockery::mock(ScheduleRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&Schedule
     */
    protected function createScheduleMock()
    {
        return Mockery::mock(Schedule::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&QueryBuilder
     */
    protected function createQueryBuilderMock()
    {
        return Mockery::mock(QueryBuilder::class);
    }

    /**
     * @covers ::findNowShowingByTheaterId
     * @test
     * @testdox findNowShowingByTheaterIdはエンティティScheduleのリストを返す
     */
    public function testFindNowShowingByTheaterId(): void
    {
        $alias     = 's';
        $theaterId = 1;

        $queryBuilderMock = $this->createQueryBuilderMock();

        $aliasShowingTheaters = 'st';
        $queryBuilderMock
            ->shouldReceive('join')
            ->once()
            ->with($alias . '.showingTheaters', $aliasShowingTheaters)
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($aliasShowingTheaters . '.theater = :theater')
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->once()
            ->with('theater', $theaterId)
            ->andReturn($queryBuilderMock);

        $result = [$this->createScheduleMock()];
        $queryBuilderMock
            ->shouldReceive('getQuery->getResult')
            ->once()
            ->with()
            ->andReturn($result);

        $scheduleRepositoryMock = $this->createScheduleRepositoryMock();
        $scheduleRepositoryMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $scheduleRepositoryMock
            ->shouldReceive('createQueryBuilder')
            ->once()
            ->with($alias)
            ->andReturn($queryBuilderMock);

        $scheduleRepositoryMock
            ->shouldReceive('addNowShowingQuery')
            ->once()
            ->with($queryBuilderMock, $alias);

        $this->assertEquals(
            $result,
            $scheduleRepositoryMock->findNowShowingByTheaterId($theaterId)
        );
    }

    /**
     * @covers ::findCommingSoonByTheaterId
     * @test
     * @testdox findCommingSoonByTheaterIdはエンティティScheduleのリストを返す
     */
    public function testFindCommingSoonByTheaterId(): void
    {
        $alias     = 's';
        $theaterId = 1;

        $queryBuilderMock = $this->createQueryBuilderMock();

        $aliasShowingTheaters = 'st';
        $queryBuilderMock
            ->shouldReceive('join')
            ->once()
            ->with($alias . '.showingTheaters', $aliasShowingTheaters)
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->once()
            ->with($aliasShowingTheaters . '.theater = :theater')
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->once()
            ->with('theater', $theaterId)
            ->andReturn($queryBuilderMock);

        $result = [$this->createScheduleMock()];
        $queryBuilderMock
            ->shouldReceive('getQuery->getResult')
            ->once()
            ->with()
            ->andReturn($result);

        $scheduleRepositoryMock = $this->createScheduleRepositoryMock();
        $scheduleRepositoryMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $scheduleRepositoryMock
            ->shouldReceive('createQueryBuilder')
            ->once()
            ->with($alias)
            ->andReturn($queryBuilderMock);

        $scheduleRepositoryMock
            ->shouldReceive('addComingSoonQuery')
            ->once()
            ->with($queryBuilderMock, $alias);

        $this->assertEquals(
            $result,
            $scheduleRepositoryMock->findCommingSoonByTheaterId($theaterId)
        );
    }
}
