<?php

/**
 * ContainerTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Session;

use Cinemasunshine\Portal\Session\Container;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * Container test
 */
final class ContainerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * test clear
     *
     * @test
     * @return void
     */
    public function testClear()
    {
        $name = 'test';

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface $storageMock */
        $storageMock = Mockery::mock('Storage');
        $storageMock
            ->shouldReceive('clear')
            ->once()
            ->with($name);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|Container $containerMock */
        $containerMock = Mockery::mock(Container::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $containerMock
            ->shouldReceive('getName')
            ->once()
            ->with()
            ->andReturn($name);

        $containerMock
            ->shouldReceive('getStorage')
            ->once()
            ->with()
            ->andReturn($storageMock);

        $containerMock->clear();
    }
}
