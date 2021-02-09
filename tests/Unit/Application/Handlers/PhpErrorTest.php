<?php

/**
 * PhpErrorTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers;

use App\Application\Handlers\PhpError;
use Exception;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

/**
 * PhpError handler test
 */
final class PhpErrorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(PhpError::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&PhpError
     */
    protected function createTargetMock()
    {
        return Mockery::mock(PhpError::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&Logger
     */
    protected function createLoggerMock()
    {
        return Mockery::mock(Logger::class);
    }

    /**
     * @test
     */
    public function testConstruct(): void
    {
        $loggerMock = $this->createLoggerMock();

        $displayErrorDetails = true;

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        // execute constructor
        $phpErrorHandlerConstructor = $targetRef->getConstructor();
        $phpErrorHandlerConstructor->invoke($targetMock, $loggerMock, $displayErrorDetails);

        // test property "logger"
        $loggerPropertyRef = $targetRef->getProperty('logger');
        $loggerPropertyRef->setAccessible(true);
        $this->assertEquals($loggerMock, $loggerPropertyRef->getValue($targetMock));

        // test property "displayErrorDetails"
        $displayErrorDetailsPropertyRef = $targetRef->getProperty('displayErrorDetails');
        $displayErrorDetailsPropertyRef->setAccessible(true);
        $this->assertEquals(
            $displayErrorDetails,
            $displayErrorDetailsPropertyRef->getValue($targetMock)
        );
    }

    /**
     * @test
     */
    public function testWriteToErrorLog(): void
    {
        $exception = new Exception();

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $targetMock
            ->shouldReceive('log')
            ->once()
            ->with($exception);

        $writeToErrorLogRef = new ReflectionMethod($targetMock, 'writeToErrorLog');
        $writeToErrorLogRef->setAccessible(true);

        // execute
        $writeToErrorLogRef->invoke($targetMock, $exception);
    }

    /**
     * @test
     */
    public function testLog(): void
    {
        $message = 'message';

        // Exceptionのmockは出来ない？
        $exception = new Exception($message);

        $loggerMock = $this->createLoggerMock();
        $loggerMock
            ->shouldReceive('error')
            ->once()
            ->with($message, Mockery::type('array'));

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $targetRef = $this->createTargetReflection();

        // test property "logger"
        $loggerPropertyRef = $targetRef->getProperty('logger');
        $loggerPropertyRef->setAccessible(true);
        $loggerPropertyRef->setValue($targetMock, $loggerMock);

        $logMethodRef = $targetRef->getMethod('log');
        $logMethodRef->setAccessible(true);

        // execute
        $logMethodRef->invoke($targetMock, $exception);
    }
}
