<?php

declare(strict_types=1);

namespace App\Controller;

use App\ORM\Entity;
use Slim\Http\Request;
use Slim\Http\Response;

class IndexController extends GeneralController
{
    /**
     * index action
     *
     * @param array<string, mixed> $args
     */
    public function executeIndex(Request $request, Response $response, array $args): Response
    {
        $mainBanners = $this->getMainBanners();

        $areaToTheaters = $this->getAreaToTheaters();

        $trailer = $this->getTrailer();

        // アップデートのため、一時的に止める SASAKI-632
        // $titleRanking = $this->getTitleRanking();

        $newsList = $this->getNewsList(Entity\News::CATEGORY_NEWS);

        $imaxNewsList = $this->getNewsList(Entity\News::CATEGORY_IMAX);

        $fourdxNewsList = $this->getNewsList(Entity\News::CATEGORY_4DX);

        $screenXNewsList = $this->getNewsList(Entity\News::CATEGORY_SCREENX);

        $fourdxScreenNewsList = $this->getNewsList(Entity\News::CATEGORY_4DX_SCREEN);

        $infoNewsList = $this->getNewsList(Entity\News::CATEGORY_INFO);

        $campaigns = $this->getCampaigns(self::PAGE_ID);

        return $this->render($response, 'index/index.html.twig', [
            'mainBanners' => $mainBanners,
            'areaToTheaters' => $areaToTheaters,
            'trailer' => $trailer,
            // 'titleRanking' => $titleRanking,
            'newsList' => $newsList,
            'imaxNewsList' => $imaxNewsList,
            'fourdxNewsList' => $fourdxNewsList,
            'screenXNewsList' => $screenXNewsList,
            'fourdxScreenNewsList' => $fourdxScreenNewsList,
            'infoNewsList' => $infoNewsList,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * @return Entity\MainBanner[]
     */
    protected function getMainBanners(): array
    {
        return $this->em
            ->getRepository(Entity\MainBanner::class)
            ->findByPageId(self::PAGE_ID);
    }

    /**
     * @return array<int, Entity\Theater[]>
     */
    protected function getAreaToTheaters(): array
    {
        $theaters = parent::getTheaters();

        $areaToTheaters = [];

        foreach ($theaters as $theater) {
            /** @var Entity\Theater $theater */
            $area = $theater->getArea();

            if (! isset($areaToTheaters[$area])) {
                $areaToTheaters[$area] = [];
            }

            $areaToTheaters[$area][] = $theater;
        }

        return $areaToTheaters;
    }

    protected function getTrailer(): ?Entity\Trailer
    {
        $trailers = $this->em
                ->getRepository(Entity\Trailer::class)
                ->findByPage(self::PAGE_ID);

        if (count($trailers) === 0) {
            return null;
        }

        // シャッフルしてランダムに１件取得する
        shuffle($trailers);

        return $trailers[0];
    }

    protected function getTitleRanking(): Entity\TitleRanking
    {
        return $this->em
            ->getRepository(Entity\TitleRanking::class)
            ->findOneById(1);
    }

    /**
     * @return Entity\News[]
     */
    protected function getNewsList(int $category): array
    {
        return $this->em
            ->getRepository(Entity\News::class)
            ->findByPage(self::PAGE_ID, $category, 8);
    }
}
