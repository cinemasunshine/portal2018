<?php

/**
 * ContainerTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Session;

use App\Session\Container;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Container test
 */
final class ContainerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function testClear(): void
    {
        $name = 'test';

        /** @var MockInterface|LegacyMockInterface $storageMock */
        $storageMock = Mockery::mock('Storage');
        $storageMock
            ->shouldReceive('clear')
            ->once()
            ->with($name);

        /** @var MockInterface|LegacyMockInterface|Container $containerMock */
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
