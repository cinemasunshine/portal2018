<?php
/**
 * ScreenXResponder.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder;

use Slim\Collection;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * ScreenX responder
 */
class ScreenXResponder extends BaseResponder
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
        return $this->view->render($response, 'screenx/index.html.twig', $data->all());
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
        return $this->view->render($response, 'screenx/about.html.twig', $data->all());
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
        return $this->view->render($response, 'screenx/schedule/list.html.twig', $data->all());
    }
}