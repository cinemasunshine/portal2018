<?php

/**
 * TheaterExtensionTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
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
     * test getFilters
     *
     * @test
     * @return void
     */
    public function testGetFilters()
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
     * test filterNameJa
     *
     * @test
     * @return void
     */
    public function testFilterNameJa()
    {
        $extensionMock = Mockery::mock(TheaterExtension::class)
            ->makePartial();

        $nameGDCS = 'グランドシネマサンシャイン';
        $this->assertStringContainsString('<br', $extensionMock->filterNameJa($nameGDCS));

        $nameOther = '平和島';
        $this->assertEquals($nameOther, $extensionMock->filterNameJa($nameOther));
    }

    /**
     * test filterNameJa2
     *
     * @test
     * @return void
     */
    public function testFilterNameJa2()
    {
        $extensionMock = Mockery::mock(TheaterExtension::class)
            ->makePartial();

        $nameGDCS = 'グランドシネマサンシャイン';
        $this->assertStringContainsString('<br', $extensionMock->filterNameJa2($nameGDCS));

        $nameOther = '平和島';
        $this->assertEquals($nameOther, $extensionMock->filterNameJa2($nameOther));
    }

    /**
     * test getFunctions
     *
     * @test
     * @return void
     */
    public function testGetFunctions()
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
     * test theaterArea
     *
     * @test
     * @return void
     */
    public function testTheaterArea()
    {
        $extensionMock = Mockery::mock(TheaterExtension::class)
            ->makePartial();

        $this->assertIsString($extensionMock->theaterArea(1));
        $this->assertNull($extensionMock->theaterArea(99));
    }

    /**
     * test getMetaKeywords
     *
     * @test
     * @return void
     */
    public function testGetMetaKeywords()
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
