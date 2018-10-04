<?php
/**
 * ImaxResponder.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder;

use Slim\Collection;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Imax responder
 */
class ImaxResponder extends BaseResponder
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
        return $this->view->render($response, 'imax/index.html.twig', $data->all());
    }
    
    /**
     * about
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function about(Response $response, Collection $data)
    {
        return $this->view->render($response, 'imax/about.html.twig', $data->all());
    }
    
    /**
     * schedule list
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function scheduleList(Response $response, Collection $data)
    {
        return $this->view->render($response, 'imax/schedule/list.html.twig', $data->all());
    }
    
    /**
     * schedule show
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function scheduleShow(Response $response, Collection $data)
    {
        return $this->view->render($response, 'imax/schedule/show.html.twig', $data->all());
    }
}
