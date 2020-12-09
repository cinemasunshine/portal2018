<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\OyakoCinemaController;
use Mockery;

final class OyakoCinemaControllerTest extends BaseTestCase
{
    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&OyakoCinemaController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(OyakoCinemaController::class);
    }

    /**
     * @test
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

        $oyakoCinemaTitles = [];
        $targetMock
            ->shouldReceive('getList')
            ->once()
            ->with()
            ->andReturn($oyakoCinemaTitles);

        $data = ['oyakoCinemaTitles' => $oyakoCinemaTitles];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'oyako_cinema/index.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeIndex($requestMock, $responseMock, $args)
        );
    }
}
