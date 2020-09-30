<?php

/**
 * TheaterListResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\Responder;

use Psr\Http\Message\ResponseInterface;
use Slim\Collection;
use Slim\Http\Response;

/**
 * Theater List responder
 */
class TheaterListResponder extends BaseResponder
{
    /**
     * index
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function index(Response $response, Collection $data)
    {
        return $this->view->render($response, 'theater_list/index.html.twig', $data->all());
    }

    /**
     * sns
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function sns(Response $response, Collection $data)
    {
        return $this->view->render($response, 'theater_list/sns.html.twig', $data->all());
    }
}
