<?php

declare(strict_types=1);

namespace Tests\Unit\Controller\API;

use App\Controller\API\ScheduleController;
use App\Schedule\Theater as TheaterSchedule;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Slim\Exception\NotFoundException;
use Tests\Unit\Controller\BaseTestCase;

final class ScheduleControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&ScheduleController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(ScheduleController::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&TheaterSchedule
     */
    protected function createTheaterScheduleAliasMock()
    {
        return Mockery::mock('alias:' . TheaterSchedule::class);
    }

    /**
     * @test
     *
     * @return void
     *
     * @doesNotPerformAssertions
     */
    public function testExecuteIndex()
    {
        // todo
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteIndexInvalidName()
    {
        $theaterName = 'invalid';

        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['name' => $theaterName];

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $theaterScheduleAliasMock = $this->createTheaterScheduleAliasMock();
        $theaterScheduleAliasMock
            ->shouldReceive('validate')
            ->once()
            ->with($theaterName)
            ->andReturn(false);

        $this->expectException(NotFoundException::class);

        $targetMock->executeIndex($requestMock, $responseMock, $args);
    }

    /**
     * @test
     *
     * @return void
     *
     * @doesNotPerformAssertions
     */
    public function testExecuteDate()
    {
        // todo
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteDateInvalidName()
    {
        $theaterName = 'invalid';

        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [
            'name' => $theaterName,
            'date' => date('Y-m-d'),
        ];

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $theaterScheduleAliasMock = $this->createTheaterScheduleAliasMock();
        $theaterScheduleAliasMock
            ->shouldReceive('validate')
            ->once()
            ->with($theaterName)
            ->andReturn(false);

        $this->expectException(NotFoundException::class);

        $targetMock->executeDate($requestMock, $responseMock, $args);
    }
}
