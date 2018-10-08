<?php
/**
 * FourdxResponder.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder;

use Slim\Collection;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Fourdx responder
 * 
 * 4DX
 */
class FourdxResponder extends BaseResponder
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
        return $this->view->render($response, '4dx/index.html.twig', $data->all());
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
        return $this->view->render($response, '4dx/about.html.twig', $data->all());
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
        return $this->view->render($response, '4dx/schedule/list.html.twig', $data->all());
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
        return $this->view->render($response, '4dx/schedule/show.html.twig', $data->all());
    }
    
    /**
     * news list
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function newsList(Response $response, Collection $data)
    {
        return $this->view->render($response, '4dx/news/list.html.twig', $data->all());
    }
}
