<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\ScheduleController;
use App\ORM\Entity\Schedule;
use App\ORM\Repository\ScheduleRepository;
use Mockery;
use Slim\Exception\NotFoundException;

final class ScheduleControllerTest extends BaseTestCase
{
    /**
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|ScheduleController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(ScheduleController::class);
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteList()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $theaters = [];
        $targetMock
            ->shouldReceive('getTheaters')
            ->once()
            ->with()
            ->andReturn($theaters);

        $nowShowingSchedules = [];
        $targetMock
            ->shouldReceive('findNowShowingSchedules')
            ->once()
            ->with()
            ->andReturn($nowShowingSchedules);

        $commingSoonSchedules = [];
        $targetMock
            ->shouldReceive('findCommingSoonSchedules')
            ->once()
            ->with()
            ->andReturn($commingSoonSchedules);

        $data = [
            'theaters' => $theaters,
            'nowShowingSchedules' => $nowShowingSchedules,
            'commingSoonSchedules' => $commingSoonSchedules,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'schedule/list.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeList($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteShow()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['schedule' => 3];

        $schedule       = $this->createScheduleMock();
        $repositoryMock = $this->createScheduleRepositoryMock();
        $repositoryMock
            ->shouldReceive('findOneById')
            ->once()
            ->with($args['schedule'])
            ->andReturn($schedule);

        $entityManagerMock = $this->createEntityManagerMock();
        $entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(Schedule::class)
            ->andReturn($repositoryMock);

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $targetMock->em = $entityManagerMock;

        $theaters = [];
        $targetMock
            ->shouldReceive('getTheaters')
            ->once()
            ->with()
            ->andReturn($theaters);

        $data = [
            'schedule' => $schedule,
            'theaters' => $theaters,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'schedule/show.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeShow($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteShowNotFound()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['schedule' => 3];

        $repositoryMock = $this->createScheduleRepositoryMock();
        $repositoryMock
            ->shouldReceive('findOneById')
            ->once()
            ->with($args['schedule'])
            ->andReturn(null);

        $entityManagerMock = $this->createEntityManagerMock();
        $entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(Schedule::class)
            ->andReturn($repositoryMock);

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $targetMock->em = $entityManagerMock;

        $this->expectException(NotFoundException::class);

        $targetMock->executeShow($requestMock, $responseMock, $args);
    }

    /**
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|ScheduleRepository
     */
    protected function createScheduleRepositoryMock()
    {
        return Mockery::mock(ScheduleRepository::class);
    }

    /**
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|Schedule
     */
    protected function createScheduleMock()
    {
        return Mockery::mock(Schedule::class);
    }
}
