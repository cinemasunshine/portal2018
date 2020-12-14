<?php

/**
 * FourdxController.php
 */

namespace App\Controller;

use App\ORM\Entity;
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
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeIndex(Request $request, Response $response, array $args)
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
     * return main_banners
     *
     * @return Entity\MainBanner[]
     */
    protected function getMainBanners()
    {
        return $this->em
            ->getRepository(Entity\MainBanner::class)
            ->findBySpecialSiteId(self::SPECIAL_SITE_ID);
    }

    /**
     * return trailers
     *
     * @return Entity\Trailer[]
     */
    protected function getTrailers()
    {
        return $this->em
            ->getRepository(Entity\Trailer::class)
            ->findBySpecialSite(self::SPECIAL_SITE_ID);
    }

    /**
     * return news list
     *
     * @param int|null $limit
     * @return Entity\News[]
     */
    protected function getNewsList(?int $limit = null)
    {
        return $this->em
            ->getRepository(Entity\News::class)
            ->findBy4dx($limit);
    }

    /**
     * return 4DX theaters
     *
     * @return Entity\Theater[]
     */
    protected function get4dxTheaters()
    {
        return $this->em
            ->getRepository(Entity\Theater::class)
            ->findBySpecialSite(self::SPECIAL_SITE_ID);
    }

    /**
     * @return Entity\Schedule[]
     */
    protected function findNowShowingSchedules(): array
    {
        return $this->em
            ->getRepository(Entity\Schedule::class)
            ->findNowShowingFor4dx();
    }

    /**
     * @return Entity\Schedule[]
     */
    protected function findCommingSoonSchedules(): array
    {
        return $this->em
            ->getRepository(Entity\Schedule::class)
            ->findCommingSoonFor4dx();
    }

    /**
     * return campaigns
     *
     * @return Entity\Campaign[]
     */
    protected function getCampaigns()
    {
        return $this->em
            ->getRepository(Entity\Campaign::class)
            ->findBySpecialSite(self::SPECIAL_SITE_ID);
    }

    /**
     * return information news list
     *
     * @param int|null $limit
     * @return Entity\News[]
     */
    protected function getInfoNewsList(?int $limit = null)
    {
        return $this->em
            ->getRepository(Entity\News::class)
            ->findBySpecialSite(self::SPECIAL_SITE_ID, Entity\News::CATEGORY_INFO, $limit);
    }

    /**
     * about action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeAbout(Request $request, Response $response, array $args)
    {
        return $this->render($response, '4dx/about.html.twig');
    }

    /**
     * schedule list action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeScheduleList(Request $request, Response $response, array $args)
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
     * schedule show action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeScheduleShow(Request $request, Response $response, array $args)
    {
        /**@var Entity\Schedule|null $schedule */
        $schedule = $this->em
            ->getRepository(Entity\Schedule::class)
            ->findOneById($args['schedule']);

        if (is_null($schedule)) {
            throw new NotFoundException($request, $response);
        }

        $theaters = $this->get4dxTheaters();

        return $this->render($response, '4dx/schedule/show.html.twig', [
            'schedule' => $schedule,
            'theaters' => $theaters,
        ]);
    }

    /**
     * news list action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeNewsList(Request $request, Response $response, array $args)
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
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeNewsShow(Request $request, Response $response, array $args)
    {
        /**@var Entity\News|null $news */
        $news = $this->em
            ->getRepository(Entity\News::class)
            ->findOneById($args['id']);

        if (is_null($news)) {
            throw new NotFoundException($request, $response);
        }

        return $this->render($response, '4dx/news/show.html.twig', ['news' => $news]);
    }

    /**
     * theater action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeTheater(Request $request, Response $response, array $args)
    {
        $theaters = $this->get4dxTheaters();

        return $this->render($response, '4dx/theater.html.twig', ['theaters' => $theaters]);
    }
}
