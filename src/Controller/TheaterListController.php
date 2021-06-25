<?php

declare(strict_types=1);

namespace App\Controller;

use App\ORM\Entity;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Theater List controller
 */
class TheaterListController extends GeneralController
{
    /**
     * index action
     *
     * @param array<string, mixed> $args
     */
    public function executeIndex(Request $request, Response $response, array $args): Response
    {
        $areaToTheaters = $this->getAreaToTheaters();

        return $this->render($response, 'theater_list/index.html.twig', ['areaToTheaters' => $areaToTheaters]);
    }

    /**
     * sns action
     *
     * @param array<string, mixed> $args
     */
    public function executeSns(Request $request, Response $response, array $args): Response
    {
        $areaToTheaters = $this->getAreaToTheaters();

        return $this->render($response, 'theater_list/sns.html.twig', ['areaToTheaters' => $areaToTheaters]);
    }

    /**
     * @return array<int, Entity\Theater[]>
     */
    protected function getAreaToTheaters(): array
    {
        $theaters = parent::findTheaters();

        $areaToTheaters = [];

        foreach ($theaters as $theater) {
            /** @var Entity\Theater $theater */
            $area = $theater->getArea();

            if (! isset($areaToTheaters[$area])) {
                $areaToTheaters[$area] = [];
            }

            $areaToTheaters[$area][] = $theater;
        }

        return $areaToTheaters;
    }
}
