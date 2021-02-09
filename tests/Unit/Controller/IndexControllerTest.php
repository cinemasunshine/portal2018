<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\IndexController;
use App\ORM\Entity\News;
use App\ORM\Entity\TitleRanking;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

final class IndexControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&IndexController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(IndexController::class);
    }

    /**
     * @test
     */
    public function testExecuteIndex(): void
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

        $areaToTheaters = [];
        $targetMock
            ->shouldReceive('getAreaToTheaters')
            ->once()
            ->with()
            ->andReturn($areaToTheaters);

        $trailer = null;
        $targetMock
            ->shouldReceive('getTrailer')
            ->once()
            ->with()
            ->andReturn($trailer);

        $titleRanking = $this->createTitleRankingMock();
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
            'areaToTheaters' => $areaToTheaters,
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

    /**
     * @return MockInterface&LegacyMockInterface&TitleRanking
     */
    protected function createTitleRankingMock()
    {
        return Mockery::mock(TitleRanking::class);
    }
}
