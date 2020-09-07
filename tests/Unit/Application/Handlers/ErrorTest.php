<?php

/**
 * ErrorTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers;

use App\Application\Handlers\Error;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

/**
 * Error handler test
 */
final class ErrorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(Error::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&Error
     */
    protected function createTargetMock()
    {
        return Mockery::mock(Error::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&Logger
     */
    protected function createLoggerMock()
    {
        return Mockery::mock(Logger::class);
    }

    /**
     * test construct
     *
     * @test
     * @return void
     */
    public function testConstruct()
    {
        $loggerMock = $this->createLoggerMock();

        $displayErrorDetails = true;

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

        // execute constructor
        $errorHandlerConstructor = $targetRef->getConstructor();
        $errorHandlerConstructor->invoke($targetMock, $loggerMock, $displayErrorDetails);

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
     * test writeToErrorLog
     *
     * @return void
     */
    public function testWriteToErrorLog()
    {
        $exception = new \Exception();

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $targetMock
            ->shouldReceive('log')
            ->once()
            ->with($exception);

        $targetRef = $this->createTargetReflection();

        $writeToErrorLogRef = $targetRef->getMethod('writeToErrorLog');
        $writeToErrorLogRef->setAccessible(true);

        // execute
        $writeToErrorLogRef->invoke($targetMock, $exception);
    }

    /**
     * test log
     *
     * @test
     * @return void
     */
    public function testLog()
    {
        $message = 'message';

        // Exceptionのmockは出来ない？
        $exception = new \Exception($message);

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
