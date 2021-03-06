<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\ImaxController;
use App\ORM\Entity\News;
use App\ORM\Entity\Schedule;
use App\ORM\Entity\Title;
use App\ORM\Repository\NewsRepository;
use App\ORM\Repository\ScheduleRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Slim\Container;
use Slim\Exception\NotFoundException;

final class ImaxControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&ImaxController
     */
    protected function createTargetMock(Container $container)
    {
        return Mockery::mock(ImaxController::class, [$container]);
    }

    /**
     * @return MockInterface&LegacyMockInterface&NewsRepository
     */
    protected function createNewsRepositoryMock()
    {
        return Mockery::mock(NewsRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&ScheduleRepository
     */
    protected function createScheduleRepositoryMock()
    {
        return Mockery::mock(ScheduleRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&News
     */
    protected function createNewsMock()
    {
        return Mockery::mock(News::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&Schedule
     */
    protected function createScheduleMock()
    {
        return Mockery::mock(Schedule::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&Title
     */
    protected function createTitleMock()
    {
        return Mockery::mock(Title::class);
    }

    /**
     * @test
     */
    public function testExecuteIndex(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $mainBanners = [];
        $targetMock
            ->shouldReceive('getMainBanners')
            ->once()
            ->with()
            ->andReturn($mainBanners);

        $trailers = [];
        $targetMock
            ->shouldReceive('getTrailers')
            ->once()
            ->with()
            ->andReturn($trailers);

        $newsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with(8)
            ->andReturn($newsList);

        $theaters = [];
        $targetMock
            ->shouldReceive('getImaxTheaters')
            ->once()
            ->with()
            ->andReturn($theaters);

        $nowShowingSchedules = [];
        $targetMock
            ->shouldReceive('findNowShowingSchedules')
            ->once()
            ->with()
            ->andReturn($nowShowingSchedules);

        $commingSoonSchedules = [];
        $targetMock
            ->shouldReceive('findCommingSoonSchedules')
            ->once()
            ->with()
            ->andReturn($commingSoonSchedules);

        $campaigns = [];
        $targetMock
            ->shouldReceive('getCampaigns')
            ->once()
            ->with()
            ->andReturn($campaigns);

        $infoNewsList = [];
        $targetMock
            ->shouldReceive('getInfoNewsList')
            ->once()
            ->with(4)
            ->andReturn($infoNewsList);

        $data = [
            'mainBanners' => $mainBanners,
            'trailers' => $trailers,
            'newsList' => $newsList,
            'theaters' => $theaters,
            'nowShowingSchedules' => $nowShowingSchedules,
            'commingSoonSchedules' => $commingSoonSchedules,
            'campaigns' => $campaigns,
            'infoNewsList' => $infoNewsList,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'imax/index.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeIndex($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteAbout(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'imax/about.html.twig')
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeAbout($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteScheduleList(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $theaters = [];
        $targetMock
            ->shouldReceive('getImaxTheaters')
            ->once()
            ->with()
            ->andReturn($theaters);

        $nowShowingSchedules = [];
        $targetMock
            ->shouldReceive('findNowShowingSchedules')
            ->once()
            ->with()
            ->andReturn($nowShowingSchedules);

        $commingSoonSchedules = [];
        $targetMock
            ->shouldReceive('findCommingSoonSchedules')
            ->once()
            ->with()
            ->andReturn($commingSoonSchedules);

        $data = [
            'theaters' => $theaters,
            'nowShowingSchedules' => $nowShowingSchedules,
            'commingSoonSchedules' => $commingSoonSchedules,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'imax/schedule/list.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeScheduleList($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteScheduleShow(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['schedule' => '3'];

        $scheduleMock = $this->createScheduleMock();

        $titleMock = $this->createTitleMock();
        $scheduleMock
            ->shouldReceive('getTitle')
            ->once()
            ->with()
            ->andReturn($titleMock);

        $scheduleRepositoryMock = $this->createScheduleRepositoryMock();
        $scheduleRepositoryMock
            ->shouldReceive('findOneById')
            ->once()
            ->with((int) $args['schedule'])
            ->andReturn($scheduleMock);

        $targetMock = $this->createTargetMock($this->createContainer());
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('getScheduleRepository')
            ->once()
            ->with()
            ->andReturn($scheduleRepositoryMock);

        $newsList = [$this->createNewsMock()];
        $targetMock
            ->shouldReceive('findNewsByTitle')
            ->once()
            ->with($titleMock, Mockery::type('int'))
            ->andReturn($newsList);

        $theaters = [];
        $targetMock
            ->shouldReceive('getImaxTheaters')
            ->once()
            ->with()
            ->andReturn($theaters);

        $data = [
            'schedule' => $scheduleMock,
            'newsList' => $newsList,
            'theaters' => $theaters,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'imax/schedule/show.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeScheduleShow($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteScheduleShowNotFound(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['schedule' => '99'];

        $scheduleRepositoryMock = $this->createScheduleRepositoryMock();
        $scheduleRepositoryMock
            ->shouldReceive('findOneById')
            ->once()
            ->with((int) $args['schedule'])
            ->andReturn(null);

        $targetMock = $this->createTargetMock($this->createContainer());
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('getScheduleRepository')
            ->once()
            ->with()
            ->andReturn($scheduleRepositoryMock);

        $this->expectException(NotFoundException::class);

        $targetMock->executeScheduleShow($requestMock, $responseMock, $args);
    }

    /**
     * @test
     */
    public function testExecuteNewsList(): void
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

        $infoNewsList = [];
        $targetMock
            ->shouldReceive('getInfoNewsList')
            ->once()
            ->with()
            ->andReturn($infoNewsList);

        $data = [
            'newsList' => $newsList,
            'infoNewsList' => $infoNewsList,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'imax/news/list.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeNewsList($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteNewsShow(): void
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
            ->with($responseMock, 'imax/news/show.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeNewsShow($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteNewsShowNotFound(): void
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

        $targetMock->executeNewsShow($requestMock, $responseMock, $args);
    }

    /**
     * @test
     */
    public function testExecuteTheater(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $container = $this->createContainer();

        $targetMock = $this->createTargetMock($container);
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $theaters = [];
        $targetMock
            ->shouldReceive('getImaxTheaters')
            ->once()
            ->with()
            ->andReturn($theaters);

        $data = ['theaters' => $theaters];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'imax/theater.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeTheater($requestMock, $responseMock, $args)
        );
    }
}
