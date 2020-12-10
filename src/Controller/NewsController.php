<?php

/**
 * NewsController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\Controller;

use App\ORM\Entity;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * News controller
 */
class NewsController extends GeneralController
{
    /**
     * list action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeList(Request $request, Response $response, array $args)
    {
        $newsList = $this->getNewsList();

        $campaigns = $this->getCampaigns(self::PAGE_ID);

        return $this->render($response, 'news/list.html.twig', [
            'newsList' => $newsList,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * return news list
     *
     * @return Entity\News[]
     */
    protected function getNewsList()
    {
        return $this->em
            ->getRepository(Entity\News::class)
            ->findByPage(self::PAGE_ID);
    }

    /**
     * show action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeShow(Request $request, Response $response, array $args)
    {
        $news = $this->em
            ->getRepository(Entity\News::class)
            ->findOneById($args['id']);

        if (is_null($news)) {
            throw new NotFoundException($request, $response);
        }

        /**@var Entity\News $news */

        return $this->render($response, 'news/show.html.twig', ['news' => $news]);
    }
}
