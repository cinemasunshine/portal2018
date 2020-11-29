<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\NewsController;
use App\ORM\Entity\News;
use App\ORM\Repository\NewsRepository;
use Mockery;
use Slim\Exception\NotFoundException;

final class NewsControllerTest extends BaseTestCase
{
    /**
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|NewsController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(NewsController::class);
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteList()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
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
            ->shouldReceive('getCampaigns')
            ->once()
            ->with(NewsController::PAGE_ID)
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
     * @return void
     */
    public function testExecuteShow()
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

        $entityManagerMock = $this->createEntityManagerMock();
        $entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(News::class)
            ->andReturn($repositoryMock);

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $targetMock->em = $entityManagerMock;

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
     * @return void
     */
    public function testExecuteShowNotFound()
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

        $entityManagerMock = $this->createEntityManagerMock();
        $entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(News::class)
            ->andReturn($repositoryMock);

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $targetMock->em = $entityManagerMock;

        $this->expectException(NotFoundException::class);

        $targetMock->executeShow($requestMock, $responseMock, $args);
    }

    /**
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|NewsRepository
     */
    protected function createNewsRepositoryMock()
    {
        return Mockery::mock(NewsRepository::class);
    }

    /**
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|News
     */
    protected function createNewsMock()
    {
        return Mockery::mock(News::class);
    }
}
