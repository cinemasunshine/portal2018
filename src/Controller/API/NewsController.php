<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\ORM\Entity\News;
use Slim\Http\Request;
use Slim\Http\Response;

class NewsController extends BaseController
{
    /**
     * @param array<string, mixed> $args
     */
    public function executePage(Request $request, Response $response, array $args): Response
    {
        $meta = ['name' => 'News list by page API'];

        $data     = [];
        $limit    = (int) $request->getParam('limit', 10);
        $newsList = $this->findNewsByPage((int) $args['page'], (int) $args['category'], $limit);

        $storagePublicEndpoint = $this->settings['storage']['public_endpoint'];

        foreach ($newsList as $news) {
            $image = $news->getImage()
                ? $news->getImage()->fileUrl($this->bc, $storagePublicEndpoint)
                : '';

            $url = $this->router->pathFor('news_show', ['id' => $news->getId()]);

            $data[] = [
                'id' => $news->getId(),
                'headline' => $news->getHeadline(),
                'image' => $image,
                'url' => $url,
            ];
        }

        return $response->withJson([
            'meta' => $meta,
            'data' => $data,
        ]);
    }

    /**
     * @return News[]
     */
    private function findNewsByPage(int $page, int $category, int $limit): array
    {
        return $this->em
            ->getRepository(News::class)
            ->findByPage($page, $category, 8);
    }
}
