<?php

namespace App\Controller;

use App\ORM\Entity;
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
     * return Special Site theaters
     *
     * @return Entity\Theater[]
     */
    protected function getSpecialSiteTheaters()
    {
        return $this->em
            ->getRepository(Entity\Theater::class)
            ->findBySpecialSite(self::SPECIAL_SITE_ID);
    }

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
     * about action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeAbout(Request $request, Response $response, array $args)
    {
        return $this->render($response, '4dx_screen/about.html.twig');
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
     * @return Entity\Schedule[]
     */
    protected function findNowShowingSchedules(): array
    {
        return $this->em
            ->getRepository(Entity\Schedule::class)
            ->findNowShowingFor4dxScreen();
    }

    /**
     * @return Entity\Schedule[]
     */
    protected function findCommingSoonSchedules(): array
    {
        return $this->em
            ->getRepository(Entity\Schedule::class)
            ->findCommingSoonFor4dxScreen();
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

        $theaters = $this->getSpecialSiteTheaters();

        return $this->render($response, '4dx_screen/schedule/show.html.twig', [
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

        return $this->render($response, '4dx_screen/news/list.html.twig', [
            'newsList' => $newsList,
            'infoNewsList' => $infoNewsList,
        ]);
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
            ->findBy4DXScreen($limit);
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

        return $this->render($response, '4dx_screen/news/show.html.twig', ['news' => $news]);
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
        $theaters = $this->getSpecialSiteTheaters();

        return $this->render($response, '4dx_screen/theater.html.twig', ['theaters' => $theaters]);
    }
}
