<?php

/**
 * TheaterListController.php
 */

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
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeIndex(Request $request, Response $response, array $args)
    {
        $areaToTheaters = $this->getTheaters();

        return $this->render($response, 'theater_list/index.html.twig', ['areaToTheaters' => $areaToTheaters]);
    }

    /**
     * sns action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeSns(Request $request, Response $response, array $args)
    {
        $areaToTheaters = $this->getTheaters();

        return $this->render($response, 'theater_list/sns.html.twig', ['areaToTheaters' => $areaToTheaters]);
    }

    /**
     * return theaters
     *
     * @return array
     */
    protected function getTheaters()
    {
        $theaters = parent::getTheaters();

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
