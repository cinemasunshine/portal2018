<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\IndexController;
use App\ORM\Entity\News;
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

        $infoNewsList = [];
        $targetMock
            ->shouldReceive('getNewsList')
            ->once()
            ->with(News::CATEGORY_INFO)
            ->andReturn($infoNewsList);

        $campaigns = [];
        $targetMock
            ->shouldReceive('findCampaigns')
            ->once()
            ->with()
            ->andReturn($campaigns);

        $data = [
            'mainBanners' => $mainBanners,
            'areaToTheaters' => $areaToTheaters,
            'trailer' => $trailer,
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
