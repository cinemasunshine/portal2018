<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\TheaterListController;
use Mockery;

final class TheaterListControllerTest extends BaseTestCase
{
    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&TheaterListController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(TheaterListController::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteIndex()
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

        $data = ['areaToTheaters' => $theaters];
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
     *
     * @return void
     */
    public function testExecuteSns()
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

        $data = ['areaToTheaters' => $theaters];
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
