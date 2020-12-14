<?php

/**
 * IndexController.php
 */

namespace App\Controller;

use App\ORM\Entity;
use Slim\Http\Request;
use Slim\Http\Response;

class IndexController extends GeneralController
{
    /**
     * index action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeIndex(Request $request, Response $response, $args)
    {
        $mainBanners = $this->getMainBanners();

        $areaToTheaters = $this->getTheaters();

        $trailer = $this->getTrailer();

        $titleRanking = $this->getTitleRanking();

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
            'titleRanking' => $titleRanking,
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
     * return main_banners
     *
     * @return Entity\MainBanner[]
     */
    protected function getMainBanners()
    {
        return $this->em
            ->getRepository(Entity\MainBanner::class)
            ->findByPageId(self::PAGE_ID);
    }

    /**
     * return theaters
     *
     * @return array
     */
    protected function getTheaters()
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

    /**
     * return trailer
     *
     * @return Entity\Trailer|null
     */
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

    /**
     * return title_raning
     *
     * @return Entity\TitleRanking
     */
    protected function getTitleRanking()
    {
        return $this->em
            ->getRepository(Entity\TitleRanking::class)
            ->findOneById(1);
    }

    /**
     * return news list
     *
     * @param int $category
     * @return Entity\News[]
     */
    protected function getNewsList(int $category)
    {
        return $this->em
            ->getRepository(Entity\News::class)
            ->findByPage(self::PAGE_ID, $category, 8);
    }
}
