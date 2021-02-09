<?php

/**
 * CommonExtensionTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Twig\Extension\CommonExtension;
use DateTime;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Common extension test
 */
final class CommonExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function testConstruct(): void
    {
        define('APP_ENV', getenv('APPSETTING_ENV'));

        $extensionMock = Mockery::mock(CommonExtension::class);

        $extensionClassRef = new ReflectionClass(CommonExtension::class);

        // execute constructor
        $constructorRef = $extensionClassRef->getConstructor();
        $constructorRef->invoke($extensionMock);

        // test property "appEnv"
        $appEnvPropertyRef = $extensionClassRef->getProperty('appEnv');
        $appEnvPropertyRef->setAccessible(true);
        $this->assertEquals(
            APP_ENV,
            $appEnvPropertyRef->getValue($extensionMock)
        );
    }

    /**
     * @test
     */
    public function testGetFunctions(): void
    {
        $extensionMock = Mockery::mock(CommonExtension::class)
            ->makePartial();

        $functions = $extensionMock->getFunctions();

        $this->assertIsArray($functions);

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }

    /**
     * @test
     */
    public function testGetAppEnv(): void
    {
        $env = 'test';

        $extensionMock = Mockery::mock(CommonExtension::class)
            ->makePartial();

        $extensionClassRef = new ReflectionClass(CommonExtension::class);

        $appEnvPropertyRef = $extensionClassRef->getProperty('appEnv');
        $appEnvPropertyRef->setAccessible(true);
        $appEnvPropertyRef->setValue($extensionMock, $env);

        $this->assertEquals($env, $extensionMock->getAppEnv());
    }

    /**
     * @test
     */
    public function testIsAppEnv(): void
    {
        $env = 'test';

        $extensionMock = Mockery::mock(CommonExtension::class)
            ->makePartial();

        $extensionClassRef = new ReflectionClass(CommonExtension::class);

        $appEnvPropertyRef = $extensionClassRef->getProperty('appEnv');
        $appEnvPropertyRef->setAccessible(true);
        $appEnvPropertyRef->setValue($extensionMock, $env);

        $this->assertTrue($extensionMock->isAppEnv($env));
        $this->assertTrue($extensionMock->isAppEnv([$env, 'dev']));

        $this->assertFalse($extensionMock->isAppEnv('dev'));
        $this->assertFalse($extensionMock->isAppEnv(['dev', 'stg']));
    }

    /**
     * @test
     */
    public function testGetFacebookUrl(): void
    {
        $name = 'example';

        $extensionMock = Mockery::mock(CommonExtension::class)
            ->makePartial();

        $this->assertStringContainsString(
            $name,
            $extensionMock->getFacebookUrl($name)
        );
    }

    /**
     * @test
     */
    public function testGetTwitterUrl(): void
    {
        $name = 'example';

        $extensionMock = Mockery::mock(CommonExtension::class)
            ->makePartial();

        $this->assertStringContainsString(
            $name,
            $extensionMock->getTwitterUrl($name)
        );
    }

    /**
     * @test
     */
    public function testGetFilters(): void
    {
        $extensionMock = Mockery::mock(CommonExtension::class)
            ->makePartial();

        $filters = $extensionMock->getFilters();

        $this->assertIsArray($filters);

        foreach ($filters as $filter) {
            $this->assertInstanceOf(TwigFilter::class, $filter);
        }
    }

    /**
     * @test
     */
    public function testWeekdayFilter(): void
    {
        $dateTimeMock = Mockery::mock(DateTime::class);
        $dateTimeMock
            ->shouldReceive('format')
            ->once()
            ->with('w')
            ->andReturn(1);

        $extensionMock = Mockery::mock(CommonExtension::class)
            ->makePartial();

        $this->assertEquals('月', $extensionMock->weekdayFilter($dateTimeMock));
    }
}
