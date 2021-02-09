<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\TheaterListController;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

final class TheaterListControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&TheaterListController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(TheaterListController::class);
    }

    /**
     * @test
     */
    public function testExecuteIndex(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $areaToTheaters = [];
        $targetMock
            ->shouldReceive('getAreaToTheaters')
            ->once()
            ->with()
            ->andReturn($areaToTheaters);

        $data = ['areaToTheaters' => $areaToTheaters];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater_list/index.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeIndex($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteSns(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $areaToTheaters = [];
        $targetMock
            ->shouldReceive('getAreaToTheaters')
            ->once()
            ->with()
            ->andReturn($areaToTheaters);

        $data = ['areaToTheaters' => $areaToTheaters];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater_list/sns.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeSns($requestMock, $responseMock, $args)
        );
    }
}
