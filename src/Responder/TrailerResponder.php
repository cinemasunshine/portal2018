<?php
/**
 * TrailerResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder;

use Slim\Collection;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Trailer responder
 */
class TrailerResponder extends BaseResponder
{
    /**
     * show
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function show(Response $response, Collection $data)
    {
        return $this->view->render($response, 'trailer/show.html.twig', $data->all());
    }
}
