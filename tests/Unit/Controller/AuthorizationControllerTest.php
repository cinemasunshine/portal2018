<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\AuthorizationController;
use Mockery;

final class AuthorizationControllerTest extends BaseTestCase
{
    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&AuthorizationController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AuthorizationController::class);
    }

    /**
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(AuthorizationController::class);
    }

    /**
     * @test
     * @return void
     */
    public function testRenderError()
    {
        $responseMock = $this->createResponseMock();

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'authorization/error.html.twig')
            ->andReturn($responseMock);

        $targetRef = $this->createTargetReflection();

        $renderErrorMethodRef = $targetRef->getMethod('renderError');
        $renderErrorMethodRef->setAccessible(true);

        $this->assertEquals(
            $responseMock,
            $renderErrorMethodRef->invoke($targetMock, $responseMock)
        );
    }
}
