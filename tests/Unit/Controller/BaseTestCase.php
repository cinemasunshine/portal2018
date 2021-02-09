<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use Doctrine\ORM\EntityManager;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;

abstract class BaseTestCase extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function createContainer(): Container
    {
        $container = new Container();

        $container['em'] = $this->createEntityManagerMock();

        return $container;
    }

    /**
     * @return MockInterface&LegacyMockInterface&Request
     */
    protected function createRequestMock()
    {
        return Mockery::mock(Request::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&Response
     */
    protected function createResponseMock()
    {
        return Mockery::mock(Response::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&Router
     */
    protected function createRouterMock()
    {
        return Mockery::mock(Router::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&EntityManager
     */
    protected function createEntityManagerMock()
    {
        return Mockery::mock(EntityManager::class);
    }
}
