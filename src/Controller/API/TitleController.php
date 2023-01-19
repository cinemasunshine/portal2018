<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\ORM\Entity\TitleRanking;
use Slim\Http\Request;
use Slim\Http\Response;

class TitleController extends BaseController
{
    /**
     * @param array<string, mixed> $args
     */
    public function executeRanking(Request $request, Response $response, array $args): Response
    {
        $meta = ['name' => 'Title ranking API'];

        $ranking = $this->findOneTitleRanking();

        $storagePublicEndpoint = $this->settings['storage']['public_endpoint'];

        $data = [
            'date_range' => [
                'from' => $ranking->getFromDate()->format('Y-m-d'),
                'to' => $ranking->getToDate()->format('Y-m-d'),
            ],
            'ranking' => [],
        ];

        foreach ($ranking->getRanks() as $rank) {
            $title = $rank->getTitle();

            if (! $title) {
                continue;
            }

            $image = $title->getImage()
                ? $title->getImage()->fileUrl($this->bc, $storagePublicEndpoint)
                : '';

            $row = [
                'rank' => $rank->getRank(),
                'url' => $rank->getDetailUrl(),
                'name' => $title->getName(),
                'image' => $image,
            ];

            $data['ranking'][] = $row;
        }

        return $response->withJson([
            'meta' => $meta,
            'data' => $data,
        ]);
    }

    private function findOneTitleRanking(): TitleRanking
    {
        return $this->em
            ->getRepository(TitleRanking::class)
            ->findOneById(1);
    }
}
