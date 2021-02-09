<?php

declare(strict_types=1);

namespace App\Controller;

use App\ORM\Entity;
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
     * @param array<string, mixed> $args
     */
    public function executeIndex(Request $request, Response $response, array $args): Response
    {
        $oyakoCinemaTitles = $this->getList();

        return $this->render($response, 'oyako_cinema/index.html.twig', ['oyakoCinemaTitles' => $oyakoCinemaTitles]);
    }

    /**
     * @return Entity\OyakoCinemaTitle[]
     */
    protected function getList(): array
    {
        return $this->em
            ->getRepository(Entity\OyakoCinemaTitle::class)
            ->findByActive();
    }
}
