<?php

declare(strict_types=1);

namespace Tests\Unit\Controller\Theater;

use App\Controller\Theater\ScheduleController;
use App\ORM\Entity\News;
use App\ORM\Entity\Schedule;
use App\ORM\Entity\Title;
use App\ORM\Repository\ScheduleRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use ReflectionClass;
use Slim\Container;
use Slim\Exception\NotFoundException;

/**
 * @coversDefaultClass \App\Controller\Theater\ScheduleController
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
     * @return MockInterface&LegacyMockInterface&Title
     */
    protected function createTitleMock()
    {
        return Mockery::mock(Title::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&News
     */
    protected function createNewsMock()
    {
        return Mockery::mock(News::class);
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
     * @testdox findNowShowingSchedulesはエンティティScheduleのリストを返す
     */
    public function testFindNowShowingSchedules(): void
    {
        $theaterId = 2;

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->once()
            ->with()
            ->andReturn($theaterId);

        $scheduleRepositoryMock = $this->createScheduleRepositoryMock();

        $result = [$this->createScheduleMock()];
        $scheduleRepositoryMock
            ->shouldReceive('findNowShowingByTheaterId')
            ->once()
            ->with($theaterId)
            ->andReturn($result);

        $scheduleControllerMock = $this->createScheduleControllerMock($this->createContainer());
        $scheduleControllerMock->shouldAllowMockingProtectedMethods();
        $scheduleControllerMock
            ->shouldReceive('getScheduleRepository')
            ->once()
            ->with()
            ->andReturn($scheduleRepositoryMock);

        $scheduleControllerRef = $this->createScheduleControllerReflection();

        $findNowShowingSchedulesMethodRef = $scheduleControllerRef->getMethod('findNowShowingSchedules');
        $findNowShowingSchedulesMethodRef->setAccessible(true);

        $this->assertEquals(
            $result,
            $findNowShowingSchedulesMethodRef->invoke($scheduleControllerMock, $theaterMock)
        );
    }

    /**
     * @covers ::findCommingSoonSchedules
     * @test
     * @testdox findCommingSoonSchedulesはエンティティScheduleのリストを返す
     */
    public function testFindCommingSoonSchedules(): void
    {
        $theaterId = 2;

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->once()
            ->with()
            ->andReturn($theaterId);

        $scheduleRepositoryMock = $this->createScheduleRepositoryMock();

        $result = [$this->createScheduleMock()];
        $scheduleRepositoryMock
            ->shouldReceive('findCommingSoonByTheaterId')
            ->once()
            ->with($theaterId)
            ->andReturn($result);

        $scheduleControllerMock = $this->createScheduleControllerMock($this->createContainer());
        $scheduleControllerMock->shouldAllowMockingProtectedMethods();
        $scheduleControllerMock
            ->shouldReceive('getScheduleRepository')
            ->once()
            ->with()
            ->andReturn($scheduleRepositoryMock);

        $scheduleControllerRef = $this->createScheduleControllerReflection();

        $findCommingSoonSchedulesMethodRef = $scheduleControllerRef->getMethod('findCommingSoonSchedules');
        $findCommingSoonSchedulesMethodRef->setAccessible(true);

        $this->assertEquals(
            $result,
            $findCommingSoonSchedulesMethodRef->invoke($scheduleControllerMock, $theaterMock)
        );
    }

    /**
     * @covers ::findOneSchedule
     * @test
     * @testdox findOneScheduleはエンティティScheduleを返す
     */
    public function testFindOneSchedule(): void
    {
        $scheduleId = 1;

        $scheduleRepositoryMock = $this->createScheduleRepositoryMock();

        $result = $this->createScheduleMock();
        $scheduleRepositoryMock
            ->shouldReceive('findOneById')
            ->once()
            ->with($scheduleId)
            ->andReturn($result);

        $scheduleControllerMock = $this->createScheduleControllerMock($this->createContainer());
        $scheduleControllerMock->shouldAllowMockingProtectedMethods();
        $scheduleControllerMock
            ->shouldReceive('getScheduleRepository')
            ->once()
            ->with()
            ->andReturn($scheduleRepositoryMock);

        $scheduleControllerRef = $this->createScheduleControllerReflection();

        $findOneScheduleMethodRef = $scheduleControllerRef->getMethod('findOneSchedule');
        $findOneScheduleMethodRef->setAccessible(true);

        $this->assertEquals(
            $result,
            $findOneScheduleMethodRef->invoke($scheduleControllerMock, $scheduleId)
        );
    }

    /**
     * @covers ::executeIndex
     * @test
     * @testdox executeIndexはテンプレートtheater/schedule/index.html.twigを描画する
     */
    public function testExecuteIndex(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $scheduleControllerMock = $this->createScheduleControllerMock($this->createContainer());
        $scheduleControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $scheduleControllerRef = $this->createScheduleControllerReflection();

        $theaterMock = $this->createTheaterMock();

        $theaterPropertyRef = $scheduleControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($scheduleControllerMock, $theaterMock);

        $nowShowingSchedules = [$this->createScheduleMock()];
        $scheduleControllerMock
            ->shouldReceive('findNowShowingSchedules')
            ->once()
            ->with($theaterMock)
            ->andReturn($nowShowingSchedules);

        $commingSoonSchedules = [$this->createScheduleMock()];
        $scheduleControllerMock
            ->shouldReceive('findCommingSoonSchedules')
            ->once()
            ->with($theaterMock)
            ->andReturn($commingSoonSchedules);

        $data = [
            'theater' => $theaterMock,
            'nowShowingSchedules' => $nowShowingSchedules,
            'commingSoonSchedules' => $commingSoonSchedules,
        ];
        $scheduleControllerMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/schedule/index.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $scheduleControllerMock->executeIndex($requestMock, $responseMock, $args)
        );
    }

    /**
     * @covers ::executeShow
     * @test
     * @testdox executeShowはScheduleが存在する場合、テンプレートtheater/schedule/show.html.twigを描画する
     */
    public function testExecuteShowCaceScheduleExists(): void
    {
        $scheduleId = '1';

        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['schedule' => $scheduleId];

        $scheduleControllerMock = $this->createScheduleControllerMock($this->createContainer());
        $scheduleControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $scheduleMock = $this->createScheduleMock();
        $scheduleControllerMock
            ->shouldReceive('findOneSchedule')
            ->once()
            ->with((int) $scheduleId)
            ->andReturn($scheduleMock);

        $titleMock = $this->createTitleMock();
        $scheduleMock
            ->shouldReceive('getTitle')
            ->once()
            ->with()
            ->andReturn($titleMock);

        $newsList = [$this->createNewsMock()];
        $scheduleControllerMock
            ->shouldReceive('findNewsByTitle')
            ->once()
            ->with($titleMock, Mockery::type('int'))
            ->andReturn($newsList);

        $scheduleControllerRef = $this->createScheduleControllerReflection();

        $theaterMock = $this->createTheaterMock();

        $theaterPropertyRef = $scheduleControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($scheduleControllerMock, $theaterMock);

        $data = [
            'theater' => $theaterMock,
            'newsList' => $newsList,
            'schedule' => $scheduleMock,
        ];
        $scheduleControllerMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/schedule/show.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $scheduleControllerMock->executeShow($requestMock, $responseMock, $args)
        );
    }

    /**
     * @covers ::executeShow
     * @test
     * @testdox executeShowはScheduleが存在しない場合、例外NotFoundExceptionをthrowする
     */
    public function testExecuteShowCaceScheduleNotExists(): void
    {
        $scheduleId = '99';

        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['schedule' => $scheduleId];

        $scheduleControllerMock = $this->createScheduleControllerMock($this->createContainer());
        $scheduleControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $scheduleControllerMock
            ->shouldReceive('findOneSchedule')
            ->once()
            ->with((int) $scheduleId)
            ->andReturn(null);

        $this->expectException(NotFoundException::class);
        $this->assertEquals(
            $responseMock,
            $scheduleControllerMock->executeShow($requestMock, $responseMock, $args)
        );
    }
}
