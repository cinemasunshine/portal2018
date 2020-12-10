<?php

/**
 * ScreenXController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\Controller;

use App\ORM\Entity;
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
            ->findByScreenX($limit);
    }

    /**
     * return ScreenX theaters
     *
     * @return Entity\Theater[]
     */
    protected function getScreenXTheaters()
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
            ->findNowShowingForScreenX();
    }

    /**
     * @return Entity\Schedule[]
     */
    protected function findCommingSoonSchedules(): array
    {
        return $this->em
            ->getRepository(Entity\Schedule::class)
            ->findCommingSoonForScreenX();
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
        return $this->render($response, 'screenx/about.html.twig');
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
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeScheduleShow(Request $request, Response $response, $args)
    {
        $schedule = $this->em
            ->getRepository(Entity\Schedule::class)
            ->findOneById($args['schedule']);

        if (is_null($schedule)) {
            throw new NotFoundException($request, $response);
        }

        /**@var Entity\Schedule $schedule */

        $theaters = $this->getScreenXTheaters();

        return $this->render($response, 'screenx/schedule/show.html.twig', [
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
     * @return string|void
     */
    public function executeNewsList(Request $request, Response $response, array $args)
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
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeNewsShow(Request $request, Response $response, array $args)
    {
        $news = $this->em
            ->getRepository(Entity\News::class)
            ->findOneById($args['id']);

        if (is_null($news)) {
            throw new NotFoundException($request, $response);
        }

        /**@var Entity\News $news */

        return $this->render($response, 'screenx/news/show.html.twig', ['news' => $news]);
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
        $theaters = $this->getScreenXTheaters();

        return $this->render($response, 'screenx/theater.html.twig', ['theaters' => $theaters]);
    }
}
