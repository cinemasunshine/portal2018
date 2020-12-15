<?php

namespace App\Controller;

use App\ORM\Entity;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * OyakoCinema controller
 */
class OyakoCinemaController extends GeneralController
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
        $oyakoCinemaTitles = $this->getList();

        return $this->render($response, 'oyako_cinema/index.html.twig', ['oyakoCinemaTitles' => $oyakoCinemaTitles]);
    }

    /**
     * return list
     *
     * @return Entity\OyakoCinemaTitle[]
     */
    protected function getList()
    {
        return $this->em
            ->getRepository(Entity\OyakoCinemaTitle::class)
            ->findByActive();
    }
}
