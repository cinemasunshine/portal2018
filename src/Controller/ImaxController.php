<?php

/**
 * ImaxController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\Controller;

use App\ORM\Entity;
use Slim\Exception\NotFoundException;

/**
 * Imax controller
 */
class ImaxController extends SpecialSiteController
{
    public const SPECIAL_SITE_ID = 1;

    /**
     * index action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeIndex($request, $response, $args)
    {
        $this->data->set('mainBanners', $this->getMainBanners());

        $this->data->set('trailers', $this->getTrailers());

        $this->data->set('newsList', $this->getNewsList(8));

        $this->data->set('theaters', $this->getImaxTheaters());

        $this->data->set('nowShowingSchedules', $this->findNowShowingSchedules());

        $this->data->set('commingSoonSchedules', $this->findCommingSoonSchedules());

        $this->data->set('campaigns', $this->getCampaigns());

        $this->data->set('infoNewsList', $this->getInfoNewsList(4));
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
            ->findByImax($limit);
    }

    /**
     * return IMAX theaters
     *
     * @return Entity\Theater[]
     */
    protected function getImaxTheaters()
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
            ->findNowShowingForImax();
    }

    /**
     * @return Entity\Schedule[]
     */
    protected function findCommingSoonSchedules(): array
    {
        return $this->em
            ->getRepository(Entity\Schedule::class)
            ->findCommingSoonForImax();
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
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeAbout($request, $response, $args)
    {
    }

    /**
     * schedule list action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeScheduleList($request, $response, $args)
    {
        $this->data->set('theaters', $this->getImaxTheaters());

        $this->data->set('nowShowingSchedules', $this->findNowShowingSchedules());

        $this->data->set('commingSoonSchedules', $this->findCommingSoonSchedules());
    }

    /**
     * schedule show action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeScheduleShow($request, $response, $args)
    {
        $schedule = $this->em
            ->getRepository(Entity\Schedule::class)
            ->findOneById($args['schedule']);

        if (is_null($schedule)) {
            throw new NotFoundException($request, $response);
        }

        /**@var Entity\Schedule $schedule */

        $this->data->set('schedule', $schedule);

        $this->data->set('theaters', $this->getImaxTheaters());
    }

    /**
     * news list action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeNewsList($request, $response, $args)
    {
        $this->data->set('newsList', $this->getNewsList());

        $this->data->set('infoNewsList', $this->getInfoNewsList());
    }

    /**
     * news show action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeNewsShow($request, $response, $args)
    {
        $news = $this->em
            ->getRepository(Entity\News::class)
            ->findOneById($args['id']);

        if (is_null($news)) {
            throw new NotFoundException($request, $response);
        }

        /**@var Entity\News $news */

        $this->data->set('news', $news);
    }

    /**
     * theater action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeTheater($request, $response, $args)
    {
        $this->data->set('theaters', $this->getImaxTheaters());
    }
}
