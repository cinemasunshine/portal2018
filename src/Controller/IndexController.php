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

        $campaigns = $this->findCampaigns();

        return $this->render($response, 'index/index.html.twig', [
            'mainBanners' => $mainBanners,
            'areaToTheaters' => $areaToTheaters,
            'trailer' => $trailer,
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
        $theaters = parent::findTheaters();

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
}
