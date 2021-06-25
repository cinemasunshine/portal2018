<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\ScheduleController;
use App\ORM\Entity\News;
use App\ORM\Entity\Schedule;
use App\ORM\Entity\Title;
use App\ORM\Repository\NewsRepository;
use App\ORM\Repository\ScheduleRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use ReflectionClass;
use Slim\Container;
use Slim\Exception\NotFoundException;

/**
 * @coversDefaultClass \App\Controller\ScheduleController
 */
final class ScheduleControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&ScheduleController
     */
    protected function createScheduleControllerMock(Container $container)
    {
        return Mockery::mock(ScheduleController::class, [$container]);
    }

    protected function createScheduleControllerReflection(): ReflectionClass
    {
        return new ReflectionClass(ScheduleController::class);
    }

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
     * @return MockInterface&LegacyMockInterface&NewsRepository
     */
    protected function createNewsRepositoryMock()
    {
        return Mockery::mock(NewsRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&News
     */
    protected function createNewsMock()
    {
        return Mockery::mock(News::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&Title
     */
    protected function createTitleMock()
    {
        return Mockery::mock(Title::class);
    }

    /**
     * @covers ::getScheduleRepository
     * @test
     * @testdox getScheduleRepositoryはエンティティScheduleのRepositoryを返す
     */
    public function testGetScheduleRepository(): void
    {
        $scheduleRepositoryMock = $this->createScheduleRepositoryMock();

        $container = $this->createContainer();

        $container['em']
            ->shouldReceive('getRepository')
            ->once()
            ->with(Schedule::class)
            ->andReturn($scheduleRepositoryMock);

        $scheduleControllerMock = $this->createScheduleControllerMock($container);

        $scheduleControllerRef = $this->createScheduleControllerReflection();

        $getScheduleRepositoryMethodRef = $scheduleControllerRef->getMethod('getScheduleRepository');
        $getScheduleRepositoryMethodRef->setAccessible(true);

        $this->assertEquals(
            $scheduleRepositoryMock,
            $getScheduleRepositoryMethodRef->invoke($scheduleControllerMock)
        );
    }

    /**
     * @covers ::findNowShowingSchedules
     * @test
     * @testdox findNowShowingSchedulesはScheduleRepository::findNowShowing()の結果を返す
     */
    public function testFindNowShowingSchedules(): void
    {
        $scheduleControllerMock = $this->createScheduleControllerMock($this->createContainer());
        $scheduleControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $result = [$this->createScheduleMock()];
        $scheduleControllerMock
            ->shouldReceive('getScheduleRepository->findNowShowing')
            ->once()
            ->with()
            ->andReturn($result);

        $scheduleControllerRef = $this->createScheduleControllerReflection();

        $findNowShowingSchedulesMethodRef = $scheduleControllerRef->getMethod('findNowShowingSchedules');
        $findNowShowingSchedulesMethodRef->setAccessible(true);

        $this->assertEquals(
            $result,
            $findNowShowingSchedulesMethodRef->invoke($scheduleControllerMock)
        );
    }

    /**
     * @covers ::findCommingSoonSchedules
     * @test
     * @testdox findCommingSoonSchedulesはScheduleRepository::findCommingSoon()の結果を返す
     */
    public function testFindCommingSoonSchedules(): void
    {
        $scheduleControllerMock = $this->createScheduleControllerMock($this->createContainer());
        $scheduleControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $result = [$this->createScheduleMock()];
        $scheduleControllerMock
            ->shouldReceive('getScheduleRepository->findCommingSoon')
            ->once()
            ->with()
            ->andReturn($result);

        $scheduleControllerRef = $this->createScheduleControllerReflection();

        $findCommingSoonSchedulesMethodRef = $scheduleControllerRef->getMethod('findCommingSoonSchedules');
        $findCommingSoonSchedulesMethodRef->setAccessible(true);

        $this->assertEquals(
            $result,
            $findCommingSoonSchedulesMethodRef->invoke($scheduleControllerMock)
        );
    }

    /**
     * @covers ::findOneSchedule
     * @test
     * @testdox findOneScheduleはScheduleRepository::findOneById()の結果を返す
     */
    public function testFindOneSchedule(): void
    {
        $scheduleControllerMock = $this->createScheduleControllerMock($this->createContainer());
        $scheduleControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $scheduleId   = 2;
        $scheduleMock = $this->createScheduleMock();
        $scheduleControllerMock
            ->shouldReceive('getScheduleRepository->findOneById')
            ->with($scheduleId)
            ->andReturn($scheduleMock, null);

        $scheduleControllerRef = $this->createScheduleControllerReflection();

        $findOneScheduleMethodRef = $scheduleControllerRef->getMethod('findOneSchedule');
        $findOneScheduleMethodRef->setAccessible(true);

        $this->assertEquals(
            $scheduleMock,
            $findOneScheduleMethodRef->invoke($scheduleControllerMock, $scheduleId)
        );
        $this->assertNull($findOneScheduleMethodRef->invoke($scheduleControllerMock, $scheduleId));
    }

    /**
     * @covers ::getNewsRepository
     * @test
     * @testdox getNewsRepositoryはエンティティNewsのRepositoryを返す
     */
    public function testGetNewsRepository(): void
    {
        $newsRepositoryMock = $this->createNewsRepositoryMock();

        $container = $this->createContainer();

        $container['em']
            ->shouldReceive('getRepository')
            ->once()
            ->with(News::class)
            ->andReturn($newsRepositoryMock);

        $scheduleControllerMock = $this->createScheduleControllerMock($container);

        $scheduleControllerRef = $this->createScheduleControllerReflection();

        $getNewsRepositoryMethodRef = $scheduleControllerRef->getMethod('getNewsRepository');
        $getNewsRepositoryMethodRef->setAccessible(true);

        $this->assertEquals(
            $newsRepositoryMock,
            $getNewsRepositoryMethodRef->invoke($scheduleControllerMock)
        );
    }

    /**
     * @return array<string,array{int,?int}>
     */
    public function findNewsByTitleDataProvider(): array
    {
        return [
            'without limit' => [
                5,
                null,
            ],
            'with limit' => [
                8,
                10,
            ],
        ];
    }

    /**
     * @covers ::findNewsByTitle
     * @dataProvider findNewsByTitleDataProvider
     * @test
     * @testdox findNewsByTitleはNewsRepository::findByTitleIdの結果を返す
     */
    public function testFindNewsByTitle(int $titleId, ?int $limit): void
    {
        $scheduleControllerMock = $this->createScheduleControllerMock($this->createContainer());
        $scheduleControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $titleMock = $this->createTitleMock();
        $titleMock
            ->shouldReceive('getId')
            ->once()
            ->with()
            ->andReturn($titleId);

        $result = [$this->createNewsMock()];
        $scheduleControllerMock
            ->shouldReceive('getNewsRepository->findByTitleId')
            ->withArgs([$titleId, $limit])
            ->andReturn($result);

        $scheduleControllerRef = $this->createScheduleControllerReflection();

        $findNewsByTitleMethodRef = $scheduleControllerRef->getMethod('findNewsByTitle');
        $findNewsByTitleMethodRef->setAccessible(true);

        $args   = [];
        $args[] = $titleMock;

        if ($limit) {
            $args[] = $limit;
        }

        $this->assertEquals(
            $result,
            $findNewsByTitleMethodRef->invokeArgs($scheduleControllerMock, $args)
        );
    }

    /**
     * @covers ::executeList
     * @test
     * @testdox executeListはテンプレートschedule/list.html.twigを描画する
     */
    public function testExecuteList(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $container = $this->createContainer();

        $scheduleControllerMock = $this->createScheduleControllerMock($container);
        $scheduleControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $theaters = [];
        $scheduleControllerMock
            ->shouldReceive('findTheaters')
            ->once()
            ->with()
            ->andReturn($theaters);

        $nowShowingSchedules = [];
        $scheduleControllerMock
            ->shouldReceive('findNowShowingSchedules')
            ->once()
            ->with()
            ->andReturn($nowShowingSchedules);

        $commingSoonSchedules = [];
        $scheduleControllerMock
            ->shouldReceive('findCommingSoonSchedules')
            ->once()
            ->with()
            ->andReturn($commingSoonSchedules);

        $data = [
            'theaters' => $theaters,
            'nowShowingSchedules' => $nowShowingSchedules,
            'commingSoonSchedules' => $commingSoonSchedules,
        ];
        $scheduleControllerMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'schedule/list.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $scheduleControllerMock->executeList($requestMock, $responseMock, $args)
        );
    }

    /**
     * @covers ::executeShow
     * @test
     * @testdox executeShowは引数のScheudleが存在する場合、テンプレートschedule/show.html.twigを描画する
     */
    public function testExecuteShowCaseScheduleExists(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['schedule' => '3'];

        $scheduleMock = $this->createScheduleMock();

        $titleMock = $this->createTitleMock();
        $scheduleMock
            ->shouldReceive('getTitle')
            ->once()
            ->with()
            ->andReturn($titleMock);

        $scheduleControllerMock = $this->createScheduleControllerMock($this->createContainer());
        $scheduleControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $scheduleControllerMock
            ->shouldReceive('findOneSchedule')
            ->once()
            ->with((int) $args['schedule'])
            ->andReturn($scheduleMock);

        $newsList = [];
        $scheduleControllerMock
            ->shouldReceive('findNewsByTitle')
            ->once()
            ->with($titleMock, Mockery::type('int'))
            ->andReturn($newsList);

        $theaters = [];
        $scheduleControllerMock
            ->shouldReceive('findTheaters')
            ->once()
            ->with()
            ->andReturn($theaters);

        $data = [
            'schedule' => $scheduleMock,
            'newsList' => $newsList,
            'theaters' => $theaters,
        ];
        $scheduleControllerMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'schedule/show.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $scheduleControllerMock->executeShow($requestMock, $responseMock, $args)
        );
    }

    /**
     * @covers ::executeShow
     * @test
     * @testdox executeShowは引数のScheudleが存在しない場合、NotFoundExceptionをthrowする
     */
    public function testExecuteShowNotFound(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['schedule' => '99'];

        $scheduleControllerMock = $this->createScheduleControllerMock($this->createContainer());
        $scheduleControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $scheduleControllerMock
            ->shouldReceive('findOneSchedule')
            ->once()
            ->with((int) $args['schedule'])
            ->andReturn(null);

        $this->expectException(NotFoundException::class);

        $scheduleControllerMock->executeShow($requestMock, $responseMock, $args);
    }
}
