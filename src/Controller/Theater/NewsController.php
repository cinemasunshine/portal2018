<?php

declare(strict_types=1);

namespace App\Controller\Theater;

use App\ORM\Entity\News;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class NewsController extends BaseController
{
    protected function findOneNewsById(int $id): ?News
    {
        return $this->getNewsRepository()
            ->findOneById($id);
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeIndex(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $newsList = $this->findNewsList($theater);

        $campaigns = $this->findCampaigns($theater);

        return $this->render($response, 'theater/news/index.html.twig', [
            'theater' => $theater,
            'newsList' => $newsList,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeShow(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $news = $this->findOneNewsById((int) $args['id']);

        if (is_null($news)) {
            throw new NotFoundException($request, $response);
        }

        return $this->render($response, 'theater/news/show.html.twig', [
            'theater' => $theater,
            'news' => $news,
        ]);
    }
}
