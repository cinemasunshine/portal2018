<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\ORM\Entity\Theater;
use App\Twig\Extension\TheaterExtension;
use InvalidArgumentException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\TheaterExtension
 */
final class TheaterExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected const DATA_FILE = __DIR__ . '/data/theater/keywords.json';

    private TheaterExtension $extension;

    /**
     * @return MockInterface&LegacyMockInterface&TheaterExtension
     */
    protected function createTheaterExtensionMock()
    {
        return Mockery::mock(TheaterExtension::class);
    }

    protected function createTheaterExtensionReflection(): ReflectionClass
    {
        return new ReflectionClass(TheaterExtension::class);
    }

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
        $this->extension = new TheaterExtension(self::DATA_FILE);
    }

    /**
     * @covers ::__construct
     * @test
     * @testdox __constructはloadKeywords()の結果をプロパティkeywordsにセットする
     */
    public function testConstruct(): void
    {
        $keywords = ['test' => 'hoge,fuga'];

        $theaterExtensionMock = $this->createTheaterExtensionMock();
        $theaterExtensionMock->shouldAllowMockingProtectedMethods();

        $theaterExtensionMock
            ->shouldReceive('loadKeywords')
            ->once()
            ->with(self::DATA_FILE)
            ->andReturn($keywords);

        $theaterExtensionRef = $this->createTheaterExtensionReflection();

        $constructorRef = $theaterExtensionRef->getConstructor();
        $constructorRef->invoke($theaterExtensionMock, self::DATA_FILE);

        $keywordsPropertyRef = $theaterExtensionRef->getProperty('keywords');
        $keywordsPropertyRef->setAccessible(true);

        $this->assertEquals($keywords, $keywordsPropertyRef->getValue($theaterExtensionMock));
    }

    /**
     * @covers ::loadKeywords
     * @test
     * @testdox loadKeywordsは引数のファイルが存在する場合、そのデータを返す
     */
    public function testLoadKeywordsCaseFileExists(): void
    {
        $theaterExtensionRef = $this->createTheaterExtensionReflection();

        $loadKeywordsMethodRef = $theaterExtensionRef->getMethod('loadKeywords');
        $loadKeywordsMethodRef->setAccessible(true);

        $theaterExtensionMock = $this->createTheaterExtensionMock();

        $result = $loadKeywordsMethodRef->invoke($theaterExtensionMock, self::DATA_FILE);

        $this->assertIsArray($result);

        foreach ($result as $row) {
            $this->assertIsString($row);
        }

        $this->assertArrayHasKey('example', $result);
        $this->assertEquals('hoge,fuga', $result['example']);
    }

    /**
     * @covers ::loadKeywords
     * @test
     * @testdox loadKeywordsは引数のファイルが存在しない場合、例外をthrowする
     */
    public function testLoadKeywordsCaseFileNotExists(): void
    {
        $theaterExtensionRef = $this->createTheaterExtensionReflection();

        $loadKeywordsMethodRef = $theaterExtensionRef->getMethod('loadKeywords');
        $loadKeywordsMethodRef->setAccessible(true);

        $theaterExtensionMock = $this->createTheaterExtensionMock();

        $this->expectException(InvalidArgumentException::class);
        $loadKeywordsMethodRef->invoke($theaterExtensionMock, __DIR__ . '/not_exist.json');
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
            'funciton theater_keywords' => ['theater_keywords'],
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
     * @covers ::getKeywords
     * @test
     * @testdox getKeywordsはkeywords設定が存在する劇場は文字列を返す
     */
    public function testGetKeywordsCaseKeywordsExist(): void
    {
        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getName')
            ->with()
            ->andReturn('example');

        $this->assertEquals('hoge,fuga', $this->extension->getKeywords($theaterMock));
    }

    /**
     * @covers ::getKeywords
     * @test
     * @testdox getKeywordsはkeywords設定が存在しない劇場はnullを返す
     */
    public function testGetMetaKeywordsCaseKeywordsNotExist(): void
    {
        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getName')
            ->with()
            ->andReturn('not_exists');

        $this->assertNull($this->extension->getKeywords($theaterMock));
    }
}
