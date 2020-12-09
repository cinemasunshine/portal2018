<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use Doctrine\ORM\EntityManager;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;

abstract class BaseTestCase extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return Container
     */
    protected function createContainer()
    {
        $container = new Container();

        $container['em'] = $this->createEntityManagerMock();

        return $container;
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&Request
     */
    protected function createRequestMock()
    {
        return Mockery::mock(Request::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&Response
     */
    protected function createResponseMock()
    {
        return Mockery::mock(Response::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&Router
     */
    protected function createRouterMock()
    {
        return Mockery::mock(Router::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&EntityManager
     */
    protected function createEntityManagerMock()
    {
        return Mockery::mock(EntityManager::class);
    }
}
