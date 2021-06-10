<?php

declare(strict_types=1);

namespace App\Controller;

use App\ORM\Entity\Campaign;
use App\ORM\Entity\MainBanner;
use App\ORM\Entity\News;
use App\ORM\Entity\Schedule;
use App\ORM\Entity\Theater;
use App\ORM\Entity\Title;
use App\ORM\Entity\Trailer;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Fourdx controller
 *
 * 4DX特設サイト
 */
class FourdxController extends SpecialSiteController
{
    public const SPECIAL_SITE_ID = 2;

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

        $theaters = $this->get4dxTheaters();

        $nowShowingSchedules = $this->findNowShowingSchedules();

        $commingSoonSchedules = $this->findCommingSoonSchedules();

        $campaigns = $this->getCampaigns();

        $infoNewsList = $this->getInfoNewsList(4);

        return $this->render($response, '4dx/index.html.twig', [
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
            ->findBy4dx($limit);
    }

    /**
     * @return Theater[]
     */
    protected function get4dxTheaters(): array
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
            ->findNowShowingFor4dx();
    }

    /**
     * @return Schedule[]
     */
    protected function findCommingSoonSchedules(): array
    {
        return $this->getScheduleRepository()
            ->findCommingSoonFor4dx();
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
        return $this->render($response, '4dx/about.html.twig');
    }

    /**
     * schedule list action
     *
     * @param array<string, mixed> $args
     */
    public function executeScheduleList(Request $request, Response $response, array $args): Response
    {
        $theaters = $this->get4dxTheaters();

        $nowShowingSchedules = $this->findNowShowingSchedules();

        $commingSoonSchedules = $this->findCommingSoonSchedules();

        return $this->render($response, '4dx/schedule/list.html.twig', [
            'theaters' => $theaters,
            'nowShowingSchedules' => $nowShowingSchedules,
            'commingSoonSchedules' => $commingSoonSchedules,
        ]);
    }

    /**
     * @return News[]
     */
    protected function findNewsByTitle(Title $title, ?int $limit = null): array
    {
        return $this->getNewsRepository()
            ->findByTitleId($title->getId(), $limit);
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

        $newsList = $this->findNewsByTitle($schedule->getTitle(), 8);

        $theaters = $this->get4dxTheaters();

        return $this->render($response, '4dx/schedule/show.html.twig', [
            'schedule' => $schedule,
            'newsList' => $newsList,
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

        return $this->render($response, '4dx/news/list.html.twig', [
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

        return $this->render($response, '4dx/news/show.html.twig', ['news' => $news]);
    }

    /**
     * theater action
     *
     * @param array<string, mixed> $args
     */
    public function executeTheater(Request $request, Response $response, array $args): Response
    {
        $theaters = $this->get4dxTheaters();

        return $this->render($response, '4dx/theater.html.twig', ['theaters' => $theaters]);
    }
}
