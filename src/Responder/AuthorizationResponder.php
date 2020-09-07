<?php

/**
 * AuthorizationResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\Responder;

use Psr\Http\Message\ResponseInterface;
use Slim\Collection;
use Slim\Http\Response;

/**
 * Authorization responder
 */
class AuthorizationResponder extends BaseResponder
{
    /**
     * error
     *
     * @param Response $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function error(Response $response, Collection $data)
    {
        return $this->view->render($response, 'authorization/error.html.twig', $data->all());
    }
}
