<?php
/**
 * TheaterResponder.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder;

use Slim\Collection;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Theater responder
 */
class TheaterResponder extends BaseResponder
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
        return $this->view->render($response, 'theater/index.html.twig', $data->all());
    }
    
    /**
     * concession
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function concession(Response $response, Collection $data)
    {
        return $this->view->render($response, 'theater/concession.html.twig', $data->all());
    }
}
