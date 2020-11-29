<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\FourdxScreenController;
use App\ORM\Entity\News;
use App\ORM\Entity\Schedule;
use App\ORM\Repository\NewsRepository;
use App\ORM\Repository\ScheduleRepository;
use Mockery;
use Slim\Exception\NotFoundException;

final class FourdxScreenControllerTest extends BaseTestCase
{
    /**
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|FourdxScreenController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(FourdxScreenController::class);
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteIndex()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
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
            ->shouldReceive('getSpecialSiteTheaters')
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
            ->with($responseMock, '4dx_screen/index.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeIndex($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteAbout()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, '4dx_screen/about.html.twig')
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeAbout($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteScheduleList()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $theaters = [];
        $targetMock
            ->shouldReceive('getSpecialSiteTheaters')
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
            ->with($responseMock, '4dx_screen/schedule/list.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeScheduleList($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteScheduleShow()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['schedule' => 3];

        $schedule       = $this->createScheduleMock();
        $repositoryMock = $this->createScheduleRepositoryMock();
        $repositoryMock
            ->shouldReceive('findOneById')
            ->once()
            ->with($args['schedule'])
            ->andReturn($schedule);

        $entityManagerMock = $this->createEntityManagerMock();
        $entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(Schedule::class)
            ->andReturn($repositoryMock);

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $targetMock->em = $entityManagerMock;

        $theaters = [];
        $targetMock
            ->shouldReceive('getSpecialSiteTheaters')
            ->once()
            ->with()
            ->andReturn($theaters);

        $data = [
            'schedule' => $schedule,
            'theaters' => $theaters,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, '4dx_screen/schedule/show.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeScheduleShow($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteScheduleShowNotFound()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['schedule' => 3];

        $repositoryMock = $this->createScheduleRepositoryMock();
        $repositoryMock
            ->shouldReceive('findOneById')
            ->once()
            ->with($args['schedule'])
            ->andReturn(null);

        $entityManagerMock = $this->createEntityManagerMock();
        $entityManagerMock
            ->shouldReceive('getRepository')
            ->once()
            ->with(Schedule::class)
            ->andReturn($repositoryMock);

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $targetMock->em = $entityManagerMock;

        $this->expectException(NotFoundException::class);

        $targetMock->executeScheduleShow($requestMock, $responseMock, $args);
    }

    /**
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|ScheduleRepository
     */
    protected function createScheduleRepositoryMock()
    {
        return Mockery::mock(ScheduleRepository::class);
    }

    /**
     * @return \Mockery\MockInterface|\Mockery\LegacyMockInterface|Schedule
     */
    protected function createScheduleMock()
    {
        return Mockery::mock(Schedule::class);
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteNewsList()
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
            ->with($responseMock, '4dx_screen/news/list.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeNewsList($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteNewsShow()
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
            ->with($responseMock, '4dx_screen/news/show.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeNewsShow($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteNewsShowNotFound()
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

        $targetMock->executeNewsShow($requestMock, $responseMock, $args);
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

    /**
     * @test
     * @return void
     */
    public function testExecuteTheater()
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $theaters = [];
        $targetMock
            ->shouldReceive('getSpecialSiteTheaters')
            ->once()
            ->with()
            ->andReturn($theaters);

        $data = ['theaters' => $theaters];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, '4dx_screen/theater.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeTheater($requestMock, $responseMock, $args)
        );
    }
}
