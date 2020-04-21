<?php

/**
 * NotFoundTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers;

use Cinemasunshine\Portal\Application\Handlers\NotFound;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Slim\Container;

/**
 * NotFound handler test
 */
final class NotFoundTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Create Container mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|Container
     */
    protected function createContainerMock()
    {
        return Mockery::mock(Container::class);
    }

    /**
     * test construct
     *
     * @test
     * @return void
     */
    public function testConstruct()
    {
        $containerMock = $this->createContainerMock();
        $notFoundHandlerMock = Mockery::mock(NotFound::class);

        // execute constructor
        $notFoundHandlerRef = new \ReflectionClass(NotFound::class);
        $notFoundHandlerConstructor = $notFoundHandlerRef->getConstructor();
        $notFoundHandlerConstructor->invoke($notFoundHandlerMock, $containerMock);

        // test property "container"
        $containerPropertyRef = $notFoundHandlerRef->getProperty('container');
        $containerPropertyRef->setAccessible(true);
        $this->assertEquals($containerMock, $containerPropertyRef->getValue($notFoundHandlerMock));
    }
}
