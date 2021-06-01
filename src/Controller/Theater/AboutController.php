<?php

declare(strict_types=1);

namespace App\Controller\Theater;

use App\ORM\Entity\News;
use App\ORM\Entity\Theater;
use Slim\Http\Request;
use Slim\Http\Response;

class AboutController extends BaseController
{
    /**
     * @return News[]
     */
    protected function findInfoNewsList(Theater $theater): array
    {
        return $this->findNewsList($theater, [News::CATEGORY_INFO], 8);
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeAccess(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $infoNewsList = $this->findInfoNewsList($theater);

        return $this->render($response, 'theater/access.html.twig', [
            'theater' => $this->theater,
            'infoNewsList' => $infoNewsList,
        ]);
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeAdmission(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $campaigns = $this->findCampaigns($theater);

        return $this->render($response, 'theater/admission.html.twig', [
            'theater' => $theater,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeConcession(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $campaigns = $this->findCampaigns($theater);

        $infoNewsList = $this->findInfoNewsList($theater);

        return $this->render($response, 'theater/concession.html.twig', [
            'theater' => $this->theater,
            'campaigns' => $campaigns,
            'infoNewsList' => $infoNewsList,
        ]);
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeFloorGuide(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $infoNewsList = $this->findInfoNewsList($theater);

        return $this->render($response, 'theater/floor_guide.html.twig', [
            'theater' => $theater,
            'infoNewsList' => $infoNewsList,
        ]);
    }
}
