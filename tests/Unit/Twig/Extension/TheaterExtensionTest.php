<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\ORM\Entity\Theater;
use App\Twig\Extension\TheaterExtension;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\TheaterExtension
 */
final class TheaterExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var TheaterExtension */
    private $extension;

    /**
     * @return MockInterface&LegacyMockInterface&Theater
     */
    private function createTheaterMock()
    {
        return Mockery::mock(Theater::class);
    }

    /**
     * @before
     */
    public function setUp(): void
    {
        $this->extension = new TheaterExtension();
    }

    /**
     * @covers ::getFilters
     * @test
     * @testdox getFiltersはTwigFilterの配列を返す
     */
    public function testGetFiltersCaseReturnType(): void
    {
        $filters = $this->extension->getFilters();

        $this->assertIsArray($filters);

        foreach ($filters as $filter) {
            $this->assertInstanceOf(TwigFilter::class, $filter);
        }
    }

    /**
     * @return array<string,array<string>>
     */
    public function getFilterNameDataProvider(): array
    {
        return [
            'filter theater_name_ja' => ['theater_name_ja'],
            'filter theater_name_ja2' => ['theater_name_ja2'],
        ];
    }

    /**
     * @covers ::getFilters
     * @dataProvider getFilterNameDataProvider
     * @test
     * @testdox getFiltersは指定のfilter名を含む
     */
    public function testGetFiltersCaseFilterNameExist(string $name): void
    {
        $filters = $this->extension->getFilters();

        $filterNames = array_map(static function (TwigFilter $filter) {
            return $filter->getName();
        }, $filters);

        $this->assertTrue(in_array($name, $filterNames));
    }

    /**
     * @covers ::filterNameJa
     * @test
     * @testdox filterNameJaはグランドシネマサンシャインはbrタグを挿入した名称を返す
     */
    public function testFilterNameJaCaseGDCS(): void
    {
        $name = 'グランドシネマサンシャイン 池袋';

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->with()
            ->andReturn(20);
        $theaterMock
            ->shouldReceive('getNameJa')
            ->with()
            ->andReturn($name);

        $this->assertStringContainsString('<br', $this->extension->filterNameJa($theaterMock));
    }

    /**
     * @covers ::filterNameJa
     * @test
     * @testdox filterNameJaはグランドシネマサンシャイン以外はそのままの名称を返す
     */
    public function testfilterNameJaCaseOtherName(): void
    {
        $name = '平和島';

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->with()
            ->andReturn(2);
        $theaterMock
            ->shouldReceive('getNameJa')
            ->with()
            ->andReturn($name);

        $this->assertEquals($name, $this->extension->filterNameJa($theaterMock));
    }

    /**
     * @covers ::filterNameJa2
     * @test
     * @testdox filterNameJa2はグランドシネマサンシャインはbrタグを挿入した名称を返す
     */
    public function testFilterNameJa2CaseGDCS(): void
    {
        $name = 'グランドシネマサンシャイン 池袋';

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->with()
            ->andReturn(20);
        $theaterMock
            ->shouldReceive('getNameJa')
            ->with()
            ->andReturn($name);

        $this->assertStringContainsString('<br', $this->extension->filterNameJa2($theaterMock));
    }

    /**
     * @covers ::filterNameJa2
     * @test
     * @testdox filterNameJa2はグランドシネマサンシャイン以外はそのままの名称を返す
     */
    public function testFilterNameJa2CaseOtherName(): void
    {
        $name = '平和島';

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->with()
            ->andReturn(2);
        $theaterMock
            ->shouldReceive('getNameJa')
            ->with()
            ->andReturn($name);

        $this->assertEquals($name, $this->extension->filterNameJa2($theaterMock));
    }

    /**
     * @covers ::getFunctions
     * @test
     * @testdox getFunctionsはTwigFunctionの配列を返す
     */
    public function testGetFunctionsCaseReturnType(): void
    {
        $functions = $this->extension->getFunctions();

        $this->assertIsArray($functions);

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }

    /**
     * @return array<string,array<string>>
     */
    public function getFunctionNameDataProvider(): array
    {
        return [
            'function theater_area' => ['theater_area'],
            'funciton theater_meta_keywords' => ['theater_meta_keywords'],
        ];
    }

    /**
     * @covers ::getFunctions
     * @dataProvider getFunctionNameDataProvider
     * @test
     * @testdox getFunctionsは指定のfunction名を含む
     */
    public function testGetFunctionsCaseFunctionNameExist(string $name): void
    {
        $functions = $this->extension->getFunctions();

        $functionNames = array_map(static function (TwigFunction $function) {
            return $function->getName();
        }, $functions);

        $this->assertTrue(in_array($name, $functionNames));
    }

    /**
     * @covers ::theaterArea
     * @test
     * @testdox theaterAreaはエリアが存在する場合は文字列を返
     */
    public function testTheaterAreaCaseAreaExist(): void
    {
        $this->assertIsString($this->extension->theaterArea(1));
    }

    /**
     * @covers ::theaterArea
     * @test
     * @testdox theaterAreaはエリアが存在しない場合はnullを返す
     */
    public function testTheaterAreaCaseAreaNotExist(): void
    {
        $this->assertNull($this->extension->theaterArea(99));
    }

    /**
     * @covers ::getMetaKeywords
     * @test
     * @testdox getMetaKeywordsはkeywords設定が存在する劇場は文字列を返す
     */
    public function testGetMetaKeywordsCaseKeywordsExist(): void
    {
        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->with()
            ->andReturn(2);

        $this->assertIsString($this->extension->getMetaKeywords($theaterMock));
    }

    /**
     * @covers ::getMetaKeywords
     * @test
     * @testdox getMetaKeywordsはkeywords設定が存在しない劇場はnullを返す
     */
    public function testGetMetaKeywordsCaseKeywordsNotExist(): void
    {
        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->with()
            ->andReturn(99);

        $this->assertNull($this->extension->getMetaKeywords($theaterMock));
    }
}
