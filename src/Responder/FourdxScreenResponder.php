<?php

/**
 * FourdxScreenResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder;

use Psr\Http\Message\ResponseInterface;
use Slim\Collection;
use Slim\Http\Response;

/**
 * FourdxScreen responder
 *
 * 4DX Screen
 */
class FourdxScreenResponder extends BaseResponder
{
    /**
     * index
     *
     * @param Response $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function index(Response $response, Collection $data)
    {
        return $this->view->render($response, '4dx_screen/index.html.twig', $data->all());
    }

    /**
     * about
     *
     * @param Response $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function about(Response $response, Collection $data)
    {
        return $this->view->render($response, '4dx_screen/about.html.twig', $data->all());
    }

    /**
     * schedule list
     *
     * @param Response $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function scheduleList(Response $response, Collection $data)
    {
        return $this->view->render($response, '4dx_screen/schedule/list.html.twig', $data->all());
    }

    /**
     * schedule show
     *
     * @param Response $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function scheduleShow(Response $response, Collection $data)
    {
        return $this->view->render($response, '4dx_screen/schedule/show.html.twig', $data->all());
    }

    /**
     * news list
     *
     * @param Response $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function newsList(Response $response, Collection $data)
    {
        return $this->view->render($response, '4dx_screen/news/list.html.twig', $data->all());
    }

    /**
     * news show
     *
     * @param Response $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function newsShow(Response $response, Collection $data)
    {
        return $this->view->render($response, '4dx_screen/news/show.html.twig', $data->all());
    }

    /**
     * theater
     *
     * @param Response $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function theater(Response $response, Collection $data)
    {
        return $this->view->render($response, '4dx_screen/theater.html.twig', $data->all());
    }
}
