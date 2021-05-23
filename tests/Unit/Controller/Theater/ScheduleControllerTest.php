<?php

declare(strict_types=1);

namespace Tests\Unit\Controller\Theater;

use App\Controller\Theater\ScheduleController;
use App\ORM\Entity\Schedule;
use App\ORM\Repository\ScheduleRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use ReflectionClass;
use Slim\Container;

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
}
