<?php

declare(strict_types=1);

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
     * @param array<string, mixed> $args
     */
    public function executeList(Request $request, Response $response, array $args): Response
    {
        $newsList = $this->getNewsList();

        $campaigns = $this->getCampaigns(self::PAGE_ID);

        return $this->render($response, 'news/list.html.twig', [
            'newsList' => $newsList,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * @return Entity\News[]
     */
    protected function getNewsList(): array
    {
        return $this->em
            ->getRepository(Entity\News::class)
            ->findByPage(self::PAGE_ID);
    }

    /**
     * show action
     *
     * @param array<string, mixed> $args
     */
    public function executeShow(Request $request, Response $response, array $args): Response
    {
        /** @var Entity\News|null $news */
        $news = $this->em
            ->getRepository(Entity\News::class)
            ->findOneById((int) $args['id']);

        if (is_null($news)) {
            throw new NotFoundException($request, $response);
        }

        return $this->render($response, 'news/show.html.twig', ['news' => $news]);
    }
}
