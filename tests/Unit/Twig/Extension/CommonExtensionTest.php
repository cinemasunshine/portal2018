<?php

/**
 * CommonExtensionTest.php
 */

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Twig\Extension\CommonExtension;
use DateTime;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Common extension test
 */
final class CommonExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private CommonExtension $commonExtension;

    protected function setUp(): void
    {
        $this->commonExtension = new CommonExtension('test');
    }

    /**
     * @test
     */
    public function testGetFunctions(): void
    {
        $functions = $this->commonExtension->getFunctions();

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
        $this->assertEquals('test', $this->commonExtension->getAppEnv());
    }

    /**
     * @test
     */
    public function testIsAppEnv(): void
    {
        $this->assertTrue($this->commonExtension->isAppEnv('test'));
        $this->assertTrue($this->commonExtension->isAppEnv(['test', 'dev']));

        $this->assertFalse($this->commonExtension->isAppEnv('dev'));
        $this->assertFalse($this->commonExtension->isAppEnv(['dev', 'stg']));
    }

    /**
     * @test
     */
    public function testGetFacebookUrl(): void
    {
        $name = 'example';

        $this->assertSame(
            'https://www.facebook.com/' . $name,
            $this->commonExtension->getFacebookUrl($name)
        );
    }

    /**
     * @test
     */
    public function testGetTwitterUrl(): void
    {
        $name = 'example';

        $this->assertSame(
            'https://twitter.com/' . $name,
            $this->commonExtension->getTwitterUrl($name)
        );
    }

    /**
     * @test
     */
    public function testGetFilters(): void
    {
        $filters = $this->commonExtension->getFilters();

        $this->assertIsArray($filters);

        foreach ($filters as $filter) {
            $this->assertInstanceOf(TwigFilter::class, $filter);
        }
    }

    /**
     * @dataProvider weekdayFilterProvider
     * @test
     */
    public function testWeekdayFilter(string $expected, string $date): void
    {
        $this->assertEquals(
            $expected,
            $this->commonExtension->weekdayFilter(new DateTime($date))
        );
    }

    /**
     * @return array{array{string,string}}
     */
    public function weekdayFilterProvider(): array
    {
        return [
            ['月', '2022-05-30'],
            ['火', '2022-05-31'],
            ['水', '2022-06-01'],
            ['木', '2022-06-02'],
            ['金', '2022-06-03'],
            ['土', '2022-06-04'],
            ['日', '2022-06-05'],
        ];
    }
}
