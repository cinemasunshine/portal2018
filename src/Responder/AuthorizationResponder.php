<?php
/**
 * AuthorizationResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder;

use Slim\Collection;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Authorization responder
 */
class AuthorizationResponder extends BaseResponder
{
    /**
     * error
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function error(Response $response, Collection $data)
    {
        return $this->view->render($response, 'authorization/error.html.twig', $data->all());
    }
}
