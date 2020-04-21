<?php

/**
 * OyakoCinemaResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder;

use Slim\Collection;
use Slim\Http\Response;

/**
 * OyakoCinema responder
 */
class OyakoCinemaResponder extends BaseResponder
{
    /**
     * index
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function index(Response $response, Collection $data)
    {
        return $this->view->render($response, 'oyako_cinema/index.html.twig', $data->all());
    }
}
