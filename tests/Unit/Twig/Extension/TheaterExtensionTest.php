<?php

/**
 * TheaterExtensionTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\ORM\Entity\Theater;
use App\Twig\Extension\TheaterExtension;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Theater extension test
 */
final class TheaterExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function testGetFilters(): void
    {
        $extensionMock = Mockery::mock(TheaterExtension::class)
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
    public function testFilterNameJa(): void
    {
        $extensionMock = Mockery::mock(TheaterExtension::class)
            ->makePartial();

        $nameGDCS = 'グランドシネマサンシャイン';
        $this->assertStringContainsString('<br', $extensionMock->filterNameJa($nameGDCS));

        $nameOther = '平和島';
        $this->assertEquals($nameOther, $extensionMock->filterNameJa($nameOther));
    }

    /**
     * @test
     */
    public function testFilterNameJa2(): void
    {
        $extensionMock = Mockery::mock(TheaterExtension::class)
            ->makePartial();

        $nameGDCS = 'グランドシネマサンシャイン';
        $this->assertStringContainsString('<br', $extensionMock->filterNameJa2($nameGDCS));

        $nameOther = '平和島';
        $this->assertEquals($nameOther, $extensionMock->filterNameJa2($nameOther));
    }

    /**
     * @test
     */
    public function testGetFunctions(): void
    {
        $extensionMock = Mockery::mock(TheaterExtension::class)
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
    public function testTheaterArea(): void
    {
        $extensionMock = Mockery::mock(TheaterExtension::class)
            ->makePartial();

        $this->assertIsString($extensionMock->theaterArea(1));
        $this->assertNull($extensionMock->theaterArea(99));
    }

    /**
     * @test
     */
    public function testGetMetaKeywords(): void
    {
        $extensionMock = Mockery::mock(TheaterExtension::class)
            ->makePartial();

        $theaterMock = Mockery::mock(Theater::class);
        $theaterMock
            ->shouldReceive('getId')
            ->with()
            ->andReturn(2, 99);

        $this->assertIsString($extensionMock->getMetaKeywords($theaterMock));
        $this->assertNull($extensionMock->getMetaKeywords($theaterMock));
    }
}
