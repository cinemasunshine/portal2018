<?php

declare(strict_types=1);

namespace App\Controller;

use App\ORM\Entity\Campaign;
use App\ORM\Entity\MainBanner;
use App\ORM\Entity\News;
use App\ORM\Entity\Schedule;
use App\ORM\Entity\Theater;
use App\ORM\Entity\Trailer;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * FourdxScreen controller
 *
 * 4DX Screen特設サイト
 */
class FourdxScreenController extends SpecialSiteController
{
    public const SPECIAL_SITE_ID = 4;

    /**
     * @return Theater[]
     */
    protected function getSpecialSiteTheaters(): array
    {
        return $this->getTheaterRepository()
            ->findBySpecialSite(self::SPECIAL_SITE_ID);
    }

    /**
     * index action
     *
     * @param array<string, mixed> $args
     */
    public function executeIndex(Request $request, Response $response, array $args): Response
    {
        $mainBanners = $this->getMainBanners();

        $trailers = $this->getTrailers();

        $newsList = $this->getNewsList(8);

        $theaters = $this->getSpecialSiteTheaters();

        $nowShowingSchedules = $this->findNowShowingSchedules();

        $commingSoonSchedules = $this->findCommingSoonSchedules();

        $campaigns = $this->getCampaigns();

        $infoNewsList = $this->getInfoNewsList(4);

        return $this->render($response, '4dx_screen/index.html.twig', [
            'mainBanners' => $mainBanners,
            'trailers' => $trailers,
            'newsList' => $newsList,
            'theaters' => $theaters,
            'nowShowingSchedules' => $nowShowingSchedules,
            'commingSoonSchedules' => $commingSoonSchedules,
            'campaigns' => $campaigns,
            'infoNewsList' => $infoNewsList,
        ]);
    }

    /**
     * @return MainBanner[]
     */
    protected function getMainBanners(): array
    {
        return $this->getMainBannerRepository()
            ->findBySpecialSiteId(self::SPECIAL_SITE_ID);
    }

    /**
     * @return Trailer[]
     */
    protected function getTrailers(): array
    {
        return $this->getTrailerRepository()
            ->findBySpecialSite(self::SPECIAL_SITE_ID);
    }

    /**
     * @return Campaign[]
     */
    protected function getCampaigns(): array
    {
        return $this->getCampaignRepository()
            ->findBySpecialSite(self::SPECIAL_SITE_ID);
    }

    /**
     * about action
     *
     * @param array<string, mixed> $args
     */
    public function executeAbout(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, '4dx_screen/about.html.twig');
    }

    /**
     * schedule list action
     *
     * @param array<string, mixed> $args
     */
    public function executeScheduleList(Request $request, Response $response, array $args): Response
    {
        $theaters = $this->getSpecialSiteTheaters();

        $nowShowingSchedules = $this->findNowShowingSchedules();

        $commingSoonSchedules = $this->findCommingSoonSchedules();

        return $this->render($response, '4dx_screen/schedule/list.html.twig', [
            'theaters' => $theaters,
            'nowShowingSchedules' => $nowShowingSchedules,
            'commingSoonSchedules' => $commingSoonSchedules,
        ]);
    }

    /**
     * @return Schedule[]
     */
    protected function findNowShowingSchedules(): array
    {
        return $this->getScheduleRepository()
            ->findNowShowingFor4dxScreen();
    }

    /**
     * @return Schedule[]
     */
    protected function findCommingSoonSchedules(): array
    {
        return $this->getScheduleRepository()
            ->findCommingSoonFor4dxScreen();
    }

    /**
     * schedule show action
     *
     * @param array<string, mixed> $args
     */
    public function executeScheduleShow(Request $request, Response $response, array $args): Response
    {
        $schedule = $this->getScheduleRepository()
            ->findOneById((int) $args['schedule']);

        if (is_null($schedule)) {
            throw new NotFoundException($request, $response);
        }

        $theaters = $this->getSpecialSiteTheaters();

        return $this->render($response, '4dx_screen/schedule/show.html.twig', [
            'schedule' => $schedule,
            'theaters' => $theaters,
        ]);
    }

    /**
     * news list action
     *
     * @param array<string, mixed> $args
     */
    public function executeNewsList(Request $request, Response $response, array $args): Response
    {
        $newsList = $this->getNewsList();

        $infoNewsList = $this->getInfoNewsList();

        return $this->render($response, '4dx_screen/news/list.html.twig', [
            'newsList' => $newsList,
            'infoNewsList' => $infoNewsList,
        ]);
    }

    /**
     * @return News[]
     */
    protected function getNewsList(?int $limit = null): array
    {
        return $this->getNewsRepository()
            ->findBy4DXScreen($limit);
    }

    /**
     * @return News[]
     */
    protected function getInfoNewsList(?int $limit = null): array
    {
        return $this->getNewsRepository()
            ->findBySpecialSite(self::SPECIAL_SITE_ID, News::CATEGORY_INFO, $limit);
    }

    /**
     * news show action
     *
     * @param array<string, mixed> $args
     */
    public function executeNewsShow(Request $request, Response $response, array $args): Response
    {
        $news = $this->getNewsRepository()
            ->findOneById((int) $args['id']);

        if (is_null($news)) {
            throw new NotFoundException($request, $response);
        }

        return $this->render($response, '4dx_screen/news/show.html.twig', ['news' => $news]);
    }

    /**
     * theater action
     *
     * @param array<string, mixed> $args
     */
    public function executeTheater(Request $request, Response $response, array $args): Response
    {
        $theaters = $this->getSpecialSiteTheaters();

        return $this->render($response, '4dx_screen/theater.html.twig', ['theaters' => $theaters]);
    }
}
