<?php
/**
 * NotAllowedTest.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Tests\Unit;

use Cinemasunshine\Portal\Application\Handlers\NotAllowed;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Slim\Container;

/**
 * NotAllowed handler test
 */
final class NotAllowedTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Create Container mock
     *
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|Container
     */
    protected function createContainerMock()
    {
        return Mockery::mock(Container::class);
    }

    /**
     * test construct
     *
     * @test
     * @return void
     */
    public function testConstruct()
    {
        $containerMock = $this->createContainerMock();
        $notAllowedHandlerMock = Mockery::mock(NotAllowed::class);

        // execute constructor
        $notAllowedHandlerRef = new \ReflectionClass(NotAllowed::class);
        $notAllowedHandlerConstructor = $notAllowedHandlerRef->getConstructor();
        $notAllowedHandlerConstructor->invoke($notAllowedHandlerMock, $containerMock);

        // test property "container"
        $containerPropertyRef = $notAllowedHandlerRef->getProperty('container');
        $containerPropertyRef->setAccessible(true);
        $this->assertEquals($containerMock, $containerPropertyRef->getValue($notAllowedHandlerMock));
    }
}
