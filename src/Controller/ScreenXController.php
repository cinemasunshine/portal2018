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
 * ScreenX controller
 */
class ScreenXController extends SpecialSiteController
{
    public const SPECIAL_SITE_ID = 3;

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

        $theaters = $this->getScreenXTheaters();

        $nowShowingSchedules = $this->findNowShowingSchedules();

        $commingSoonSchedules = $this->findCommingSoonSchedules();

        $campaigns = $this->getCampaigns();

        $infoNewsList = $this->getInfoNewsList(4);

        return $this->render($response, 'screenx/index.html.twig', [
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
     * @return News[]
     */
    protected function getNewsList(?int $limit = null): array
    {
        return $this->getNewsRepository()
            ->findByScreenX($limit);
    }

    /**
     * @return Theater[]
     */
    protected function getScreenXTheaters(): array
    {
        return $this->getTheaterRepository()
            ->findBySpecialSite(self::SPECIAL_SITE_ID);
    }

    /**
     * @return Schedule[]
     */
    protected function findNowShowingSchedules(): array
    {
        return $this->getScheduleRepository()
            ->findNowShowingForScreenX();
    }

    /**
     * @return Schedule[]
     */
    protected function findCommingSoonSchedules(): array
    {
        return $this->getScheduleRepository()
            ->findCommingSoonForScreenX();
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
     * @return News[]
     */
    protected function getInfoNewsList(?int $limit = null): array
    {
        return $this->getNewsRepository()
            ->findBySpecialSite(self::SPECIAL_SITE_ID, News::CATEGORY_INFO, $limit);
    }

    /**
     * about action
     *
     * @param array<string, mixed> $args
     */
    public function executeAbout(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'screenx/about.html.twig');
    }

    /**
     * schedule list action
     *
     * @param array<string, mixed> $args
     */
    public function executeScheduleList(Request $request, Response $response, array $args): Response
    {
        $theaters = $this->getScreenXTheaters();

        $nowShowingSchedules = $this->findNowShowingSchedules();

        $commingSoonSchedules = $this->findCommingSoonSchedules();

        return $this->render($response, 'screenx/schedule/list.html.twig', [
            'theaters' => $theaters,
            'nowShowingSchedules' => $nowShowingSchedules,
            'commingSoonSchedules' => $commingSoonSchedules,
        ]);
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

        $theaters = $this->getScreenXTheaters();

        return $this->render($response, 'screenx/schedule/show.html.twig', [
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

        return $this->render($response, 'screenx/news/list.html.twig', [
            'newsList' => $newsList,
            'infoNewsList' => $infoNewsList,
        ]);
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

        return $this->render($response, 'screenx/news/show.html.twig', ['news' => $news]);
    }

    /**
     * theater action
     *
     * @param array<string, mixed> $args
     */
    public function executeTheater(Request $request, Response $response, array $args): Response
    {
        $theaters = $this->getScreenXTheaters();

        return $this->render($response, 'screenx/theater.html.twig', ['theaters' => $theaters]);
    }
}
