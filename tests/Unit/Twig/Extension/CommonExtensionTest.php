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
     * test construct
     *
     * @test
     *
     * @return void
     */
    public function testConstruct()
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
     * test getFunctions
     *
     * @test
     *
     * @return void
     */
    public function testGetFunctions()
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
     * test getAppEnv
     *
     * @test
     *
     * @return void
     */
    public function testGetAppEnv()
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
     * test isAppEnv
     *
     * @test
     *
     * @return void
     */
    public function testIsAppEnv()
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
     * test getFacebookUrl
     *
     * @test
     *
     * @return void
     */
    public function testGetFacebookUrl()
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
     * test getTwitterUrl
     *
     * @test
     *
     * @return void
     */
    public function testGetTwitterUrl()
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
     * test getFilters
     *
     * @test
     *
     * @return void
     */
    public function testGetFilters()
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
     * test weekdayFilter
     *
     * @test
     *
     * @return void
     */
    public function testWeekdayFilter()
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
