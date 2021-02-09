<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\AuthorizationController;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use ReflectionClass;

final class AuthorizationControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&AuthorizationController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AuthorizationController::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(AuthorizationController::class);
    }

    /**
     * @test
     */
    public function testRenderError(): void
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
