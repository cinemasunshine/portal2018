<?php

/**
 * TheaterController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\Controller;

use App\ORM\Entity;
use Slim\Exception\NotFoundException;

/**
 * Theater controller
 */
class TheaterController extends BaseController
{
    /** @var Entity\Theater */
    protected $theater;

    /**
     * {@inheritDoc}
     */
    protected function preExecute($request, $response, $args): void
    {
        $theater = $this->getTheater($args['name']);

        if (is_null($theater)) {
            throw new NotFoundException($request, $response);
        }

        /**@var Entity\Theater $theater */

        $this->theater = $theater;
    }

    /**
     * {@inheritDoc}
     */
    protected function postExecute($request, $response, $args): void
    {
        $session = $this->sm->getContainer();

        /**
         * 閲覧した劇場ページ
         * ログイン、ログアウトのリダイレクト先として保存
         */
        $session['viewed_theater'] = $this->theater->getName();
    }

    /**
     * find by entity
     *
     * @param string $name
     * @return Entity\Theater|null
     */
    protected function getTheater(string $name)
    {
        return $this->em
            ->getRepository(Entity\Theater::class)
            ->findOneByName($name);
    }

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
        $theater = $this->theater;

        $this->data->set('theater', $theater);

        $this->data->set('mainBanners', $this->getMainBanners($theater));

        $this->data->set('infoNewsList', $this->getNewsList(
            $theater,
            Entity\News::CATEGORY_INFO,
            8
        ));

        if ($theater->isStatusClosed()) {
            return 'closed';
        }

        $this->data->set('trailer', $this->getTrailer($theater));

        $this->data->set('eventNewsList', $this->getNewsList(
            $theater,
            Entity\News::CATEGORY_EVENT,
            8
        ));

        $this->data->set('newsList', $this->getNewsList(
            $theater,
            [
                Entity\News::CATEGORY_NEWS, // SASAKI-271
                Entity\News::CATEGORY_IMAX, // SASAKI-271
                Entity\News::CATEGORY_4DX, // SASAKI-271
                Entity\News::CATEGORY_SCREENX, // SASAKI-351
                Entity\News::CATEGORY_4DX_SCREEN, // SASAKI-482
            ],
            8
        ));

        $this->data->set('campaigns', $this->getCampaigns($theater));
    }

    /**
     * return main_banners
     *
     * @param Entity\Theater $theater
     * @return Entity\MainBanner[]
     */
    protected function getMainBanners(Entity\Theater $theater)
    {
        return $this->em
            ->getRepository(Entity\MainBanner::class)
            ->findByTheaterId($theater->getId());
    }

    /**
     * return campaigns
     *
     * @param Entity\Theater $theater
     * @return Entity\Campaign[]
     */
    protected function getCampaigns(Entity\Theater $theater)
    {
        return $this->em
            ->getRepository(Entity\Campaign::class)
            ->findByTheater($theater->getId());
    }

    /**
     * return trailer
     *
     * @param Entity\Theater $theater
     * @return Entity\Trailer|null
     */
    protected function getTrailer(Entity\Theater $theater): ?Entity\Trailer
    {
        $trailers = $this->em
                ->getRepository(Entity\Trailer::class)
                ->findByTheater($theater->getId());

        if (count($trailers) === 0) {
            return null;
        }

        // シャッフルしてランダムに１件取得する
        shuffle($trailers);

        return $trailers[0];
    }

    /**
     * access action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeAccess($request, $response, $args)
    {
        $theater = $this->theater;

        $this->data->set('theater', $theater);

        $this->data->set('infoNewsList', $this->getNewsList(
            $theater,
            Entity\News::CATEGORY_INFO,
            8
        ));
    }

    /**
     * admission action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeAdmission($request, $response, $args)
    {
        $theater = $this->theater;

        $this->data->set('theater', $theater);

        $this->data->set('campaigns', $this->getCampaigns($theater));
    }

    /**
     * advance ticket action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeAdvanceTicket($request, $response, $args)
    {
        $theater = $this->theater;

        $this->data->set('theater', $theater);

        $this->data->set('advanceTickets', $this->getAdvanceTickets($theater->getId()));

        $this->data->set('campaigns', $this->getCampaigns($theater));

        $this->data->set('infoNewsList', $this->getNewsList(
            $theater,
            Entity\News::CATEGORY_INFO,
            8
        ));
    }

    /**
     * return advance tickets
     *
     * @param int $theaterId
     * @return Entity\AdvanceTicket[]
     */
    protected function getAdvanceTickets(int $theaterId)
    {
        return $this->em
            ->getRepository(Entity\AdvanceTicket::class)
            ->findByTheater($theaterId);
    }

    /**
     * concession action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeConcession($request, $response, $args)
    {
        $theater = $this->theater;

        $this->data->set('theater', $theater);

        $this->data->set('campaigns', $this->getCampaigns($theater));

        $this->data->set('infoNewsList', $this->getNewsList(
            $theater,
            Entity\News::CATEGORY_INFO,
            8
        ));
    }

    /**
     * floor guide action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeFloorGuide($request, $response, $args)
    {
        $theater = $this->theater;

        $this->data->set('theater', $theater);

        $this->data->set('infoNewsList', $this->getNewsList(
            $theater,
            Entity\News::CATEGORY_INFO,
            8
        ));
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
        $theater = $this->theater;

        $this->data->set('theater', $theater);

        $this->data->set('newsList', $this->getNewsList($theater));

        $this->data->set('campaigns', $this->getCampaigns($theater));
    }

    /**
     * return news list
     *
     * @param Entity\Theater $theater
     * @param array|int      $category
     * @param int|null       $limit
     * @return Entity\News[]
     */
    protected function getNewsList(Entity\Theater $theater, $category = [], ?int $limit = null)
    {
        if (!is_array($category)) {
            $category = [ $category ];
        }

        return $this->em
            ->getRepository(Entity\News::class)
            ->findByTheater($theater->getId(), $category, $limit);
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
        $theater = $this->theater;

        $this->data->set('theater', $theater);

        $news = $this->em
            ->getRepository(Entity\News::class)
            ->findOneById($args['id']);

        if (is_null($news)) {
            throw new NotFoundException($request, $response);
        }

        /**@var Entity\News $news */

        $this->data->set('news', $news);
    }
}
