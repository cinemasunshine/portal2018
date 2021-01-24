<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\TheaterController;
use App\ORM\Entity\News;
use App\ORM\Entity\Theater;
use App\ORM\Repository\NewsRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use ReflectionClass;
use Slim\Container;
use Slim\Exception\NotFoundException;

final class TheaterControllerTest extends BaseTestCase
{
    /**
     * @param Container $container
     * @return MockInterface&LegacyMockInterface&TheaterController
     */
    protected function createTargetMock(Container $container)
    {
        return Mockery::mock(TheaterController::class, [$container]);
    }

    /**
     * @return ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new ReflectionClass(TheaterController::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&Theater
     */
    protected function createTheaterMock()
    {
        return Mockery::mock(Theater::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteIndexOpen()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('isStatusClosed')
            ->once()
            ->with()
            ->andReturn(false);

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetRef = $this->createTargetReflection();

        $theaterPropertyRef = $targetRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($targetMock, $theaterMock);

        $mainBanners = [];
        $targetMock
            ->shouldReceive('getMainBanners')
            ->once()
            ->with($theaterMock)
            ->andReturn($mainBanners);

        $infoNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with($theaterMock, News::CATEGORY_INFO, 8)
            ->andReturn($infoNewsList);

        $trailer = null;
        $targetMock
            ->shouldReceive('getTrailer')
            ->once()
            ->with($theaterMock)
            ->andReturn($trailer);

        $eventNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with($theaterMock, News::CATEGORY_EVENT, 8)
            ->andReturn($eventNewsList);

        $newsList           = [];
        $newsListCategories = [
            News::CATEGORY_NEWS,
            News::CATEGORY_IMAX,
            News::CATEGORY_4DX,
            News::CATEGORY_SCREENX,
            News::CATEGORY_4DX_SCREEN,
        ];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with($theaterMock, $newsListCategories, 8)
            ->andReturn($newsList);

        $campaigns = [];
        $targetMock
            ->shouldReceive('getCampaigns')
            ->once()
            ->with($theaterMock)
            ->andReturn($campaigns);

        $data = [
            'theater' => $theaterMock,
            'mainBanners' => $mainBanners,
            'infoNewsList' => $infoNewsList,
            'trailer' => $trailer,
            'eventNewsList' => $eventNewsList,
            'newsList' => $newsList,
            'campaigns' => $campaigns,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/index/index.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeIndex($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteIndexClose()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('isStatusClosed')
            ->once()
            ->with()
            ->andReturn(true);

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetRef = $this->createTargetReflection();

        $theaterPropertyRef = $targetRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($targetMock, $theaterMock);

        $mainBanners = [];
        $targetMock
            ->shouldReceive('getMainBanners')
            ->once()
            ->with($theaterMock)
            ->andReturn($mainBanners);

        $infoNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with($theaterMock, News::CATEGORY_INFO, 8)
            ->andReturn($infoNewsList);

        $data = [
            'theater' => $theaterMock,
            'mainBanners' => $mainBanners,
            'infoNewsList' => $infoNewsList,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/index/closed.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeIndex($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteAccess()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $theaterMock = $this->createTheaterMock();

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetRef = $this->createTargetReflection();

        $theaterPropertyRef = $targetRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($targetMock, $theaterMock);

        $infoNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with($theaterMock, News::CATEGORY_INFO, 8)
            ->andReturn($infoNewsList);

        $data = [
            'theater' => $theaterMock,
            'infoNewsList' => $infoNewsList,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/access.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeAccess($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteAdmission()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $theaterMock = $this->createTheaterMock();

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetRef = $this->createTargetReflection();

        $theaterPropertyRef = $targetRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($targetMock, $theaterMock);

        $campaigns = [];
        $targetMock
            ->shouldReceive('getCampaigns')
            ->once()
            ->with($theaterMock)
            ->andReturn($campaigns);

        $data = [
            'theater' => $theaterMock,
            'campaigns' => $campaigns,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/admission.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeAdmission($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteAdvanceTicket()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $theaterMock = $this->createTheaterMock();
        $theaterId   = 2;
        $theaterMock
            ->shouldReceive('getId')
            ->once()
            ->with()
            ->andReturn($theaterId);

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetRef = $this->createTargetReflection();

        $theaterPropertyRef = $targetRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($targetMock, $theaterMock);

        $advanceTickets = [];
        $targetMock
            ->shouldReceive('getAdvanceTickets')
            ->once()
            ->with($theaterId)
            ->andReturn($advanceTickets);

        $campaigns = [];
        $targetMock
            ->shouldReceive('getCampaigns')
            ->once()
            ->with($theaterMock)
            ->andReturn($campaigns);

        $infoNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with($theaterMock, News::CATEGORY_INFO, 8)
            ->andReturn($infoNewsList);

        $data = [
            'theater' => $theaterMock,
            'advanceTickets' => $advanceTickets,
            'campaigns' => $campaigns,
            'infoNewsList' => $infoNewsList,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/advance_ticket.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeAdvanceTicket($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteConcession()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $theaterMock = $this->createTheaterMock();

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetRef = $this->createTargetReflection();

        $theaterPropertyRef = $targetRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($targetMock, $theaterMock);

        $campaigns = [];
        $targetMock
            ->shouldReceive('getCampaigns')
            ->once()
            ->with($theaterMock)
            ->andReturn($campaigns);

        $infoNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with($theaterMock, News::CATEGORY_INFO, 8)
            ->andReturn($infoNewsList);

        $data = [
            'theater' => $theaterMock,
            'campaigns' => $campaigns,
            'infoNewsList' => $infoNewsList,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/concession.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeConcession($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteFloorGuide()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $theaterMock = $this->createTheaterMock();

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetRef = $this->createTargetReflection();

        $theaterPropertyRef = $targetRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($targetMock, $theaterMock);

        $infoNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with($theaterMock, News::CATEGORY_INFO, 8)
            ->andReturn($infoNewsList);

        $data = [
            'theater' => $theaterMock,
            'infoNewsList' => $infoNewsList,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/floor_guide.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeFloorGuide($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteNewsList()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $theaterMock = $this->createTheaterMock();

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetRef = $this->createTargetReflection();

        $theaterPropertyRef = $targetRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($targetMock, $theaterMock);

        $newsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with($theaterMock)
            ->andReturn($newsList);

        $campaigns = [];
        $targetMock
            ->shouldReceive('getCampaigns')
            ->once()
            ->with($theaterMock)
            ->andReturn($campaigns);

        $data = [
            'theater' => $theaterMock,
            'newsList' => $newsList,
            'campaigns' => $campaigns,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/news/list.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeNewsList($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteNewsShow()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['id' => 4];

        $theaterMock = $this->createTheaterMock();

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

        $targetRef = $this->createTargetReflection();

        $theaterPropertyRef = $targetRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($targetMock, $theaterMock);

        $data = [
            'theater' => $theaterMock,
            'news' => $news,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/news/show.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeNewsShow($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteNewsShowNotFound()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['id' => 4];

        $theaterMock = $this->createTheaterMock();

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

        $targetRef = $this->createTargetReflection();

        $theaterPropertyRef = $targetRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($targetMock, $theaterMock);

        $this->expectException(NotFoundException::class);

        $targetMock->executeNewsShow($requestMock, $responseMock, $args);
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
