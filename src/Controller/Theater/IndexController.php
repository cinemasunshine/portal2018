<?php

declare(strict_types=1);

namespace App\Controller\Theater;

use App\ORM\Entity\MainBanner;
use App\ORM\Entity\News;
use App\ORM\Entity\Theater;
use App\ORM\Entity\Trailer;
use App\ORM\Repository\MainBannerRepository;
use App\ORM\Repository\TrailerRepository;
use Slim\Http\Request;
use Slim\Http\Response;

class IndexController extends BaseController
{
    protected function getMainBannerRepository(): MainBannerRepository
    {
        return $this->em->getRepository(MainBanner::class);
    }

    /**
     * @return MainBanner[]
     */
    protected function findMainBanners(Theater $theater): array
    {
        return $this->getMainBannerRepository()
            ->findByTheaterId($theater->getId());
    }

    protected function getTrailerRepository(): TrailerRepository
    {
        return $this->em->getRepository(Trailer::class);
    }

    protected function findOneTrailer(Theater $theater): ?Trailer
    {
        $trailers = $this->getTrailerRepository()
            ->findByTheater($theater->getId());

        if (count($trailers) === 0) {
            return null;
        }

        // シャッフルしてランダムに１件取得する
        shuffle($trailers);

        return $trailers[0];
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeShow(Request $request, Response $response, array $args): Response
    {
        if ($this->theater->isStatusClosed()) {
            return $this->executeShowClosed($request, $response, $args);
        }

        return $this->executeShowOpen($request, $response, $args);
    }

    /**
     * @param array<string, mixed> $args
     */
    protected function executeShowOpen(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $mainBanners = $this->findMainBanners($theater);

        $trailer = $this->findOneTrailer($theater);

        $infoNewsList = $this->findNewsList(
            $theater,
            [News::CATEGORY_INFO],
            8
        );

        $eventNewsList = $this->findNewsList(
            $theater,
            [News::CATEGORY_EVENT],
            8
        );

        $newsList = $this->findNewsList(
            $theater,
            [
                News::CATEGORY_NEWS, // SASAKI-271
                News::CATEGORY_IMAX, // SASAKI-271
                News::CATEGORY_4DX, // SASAKI-271
                News::CATEGORY_SCREENX, // SASAKI-351
                News::CATEGORY_4DX_SCREEN, // SASAKI-482
            ],
            8
        );

        $campaigns = $this->findCampaigns($theater);

        return $this->render($response, 'theater/index/index.html.twig', [
            'theater' => $theater,
            'mainBanners' => $mainBanners,
            'trailer' => $trailer,
            'infoNewsList' => $infoNewsList,
            'eventNewsList' => $eventNewsList,
            'newsList' => $newsList,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * @param array<string, mixed> $args
     */
    protected function executeShowClosed(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $mainBanners = $this->findMainBanners($theater);

        $infoNewsList = $this->findNewsList(
            $theater,
            [News::CATEGORY_INFO],
            8
        );

        return $this->render($response, 'theater/index/closed.html.twig', [
            'theater' => $theater,
            'mainBanners' => $mainBanners,
            'infoNewsList' => $infoNewsList,
        ]);
    }
}
