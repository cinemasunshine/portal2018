<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\NewsController;
use App\ORM\Entity\News;
use App\ORM\Repository\NewsRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Slim\Container;
use Slim\Exception\NotFoundException;

final class NewsControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&NewsController
     */
    protected function createTargetMock(Container $container)
    {
        return Mockery::mock(NewsController::class, [$container]);
    }

    /**
     * @test
     */
    public function testExecuteList(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $newsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with()
            ->andReturn($newsList);

        $campaigns = [];
        $targetMock
            ->shouldReceive('findCampaigns')
            ->once()
            ->with()
            ->andReturn($campaigns);

        $data = [
            'newsList' => $newsList,
            'campaigns' => $campaigns,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'news/list.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeList($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteShow(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['id' => 4];

        $news           = $this->createNewsMock();
        $repositoryMock = $this->createNewsRepositoryMock();
        $repositoryMock
            ->shouldReceive('findOneById')
            ->once()
            ->with($args['id'])
            ->andReturn($news);

        $container = $this->createContainer();

        $container['em']
            ->shouldReceive('getRepository')
            ->once()
            ->with(News::class)
            ->andReturn($repositoryMock);

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $data = ['news' => $news];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'news/show.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeShow($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteShowNotFound(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['id' => 4];

        $repositoryMock = $this->createNewsRepositoryMock();
        $repositoryMock
            ->shouldReceive('findOneById')
            ->once()
            ->with($args['id'])
            ->andReturn(null);

        $container = $this->createContainer();

        $container['em']
            ->shouldReceive('getRepository')
            ->once()
            ->with(News::class)
            ->andReturn($repositoryMock);

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $this->expectException(NotFoundException::class);

        $targetMock->executeShow($requestMock, $responseMock, $args);
    }

    /**
     * @return MockInterface&LegacyMockInterface&NewsRepository
     */
    protected function createNewsRepositoryMock()
    {
        return Mockery::mock(NewsRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&News
     */
    protected function createNewsMock()
    {
        return Mockery::mock(News::class);
    }
}
