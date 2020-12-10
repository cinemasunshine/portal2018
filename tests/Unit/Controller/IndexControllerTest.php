<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\IndexController;
use App\ORM\Entity\News;
use Mockery;

final class IndexControllerTest extends BaseTestCase
{
    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&IndexController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(IndexController::class);
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

        $theaters = [];
        $targetMock
            ->shouldReceive('getTheaters')
            ->once()
            ->with()
            ->andReturn($theaters);

        $trailer = null;
        $targetMock
            ->shouldReceive('getTrailer')
            ->once()
            ->with()
            ->andReturn($trailer);

        $titleRanking = null;
        $targetMock
            ->shouldReceive('getTitleRanking')
            ->once()
            ->with()
            ->andReturn($titleRanking);

        $newsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with(News::CATEGORY_NEWS)
            ->andReturn($newsList);

        $imaxNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with(News::CATEGORY_IMAX)
            ->andReturn($imaxNewsList);

        $fourdxNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with(News::CATEGORY_4DX)
            ->andReturn($fourdxNewsList);

        $screenXNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with(News::CATEGORY_SCREENX)
            ->andReturn($screenXNewsList);

        $fourdxScreenNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with(News::CATEGORY_4DX_SCREEN)
            ->andReturn($fourdxScreenNewsList);

        $infoNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with(News::CATEGORY_INFO)
            ->andReturn($infoNewsList);

        $campaigns = [];
        $targetMock
            ->shouldReceive('getCampaigns')
            ->once()
            ->with(IndexController::PAGE_ID)
            ->andReturn($campaigns);

        $data = [
            'mainBanners' => $mainBanners,
            'areaToTheaters' => $theaters,
            'trailer' => $trailer,
            'titleRanking' => $titleRanking,
            'newsList' => $newsList,
            'imaxNewsList' => $imaxNewsList,
            'fourdxNewsList' => $fourdxNewsList,
            'screenXNewsList' => $screenXNewsList,
            'fourdxScreenNewsList' => $fourdxScreenNewsList,
            'infoNewsList' => $infoNewsList,
            'campaigns' => $campaigns,
        ];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'index/index.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeIndex($requestMock, $responseMock, $args)
        );
    }
}
