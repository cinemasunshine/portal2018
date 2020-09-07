<?php

/**
 * ScreenXResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\Responder;

use Psr\Http\Message\ResponseInterface;
use Slim\Collection;
use Slim\Http\Response;

/**
 * ScreenX responder
 */
class ScreenXResponder extends BaseResponder
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
        return $this->view->render($response, 'screenx/index.html.twig', $data->all());
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
        return $this->view->render($response, 'screenx/about.html.twig', $data->all());
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
        return $this->view->render($response, 'screenx/schedule/list.html.twig', $data->all());
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
        return $this->view->render($response, 'screenx/schedule/show.html.twig', $data->all());
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
        return $this->view->render($response, 'screenx/news/list.html.twig', $data->all());
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
        return $this->view->render($response, 'screenx/news/show.html.twig', $data->all());
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
        return $this->view->render($response, 'screenx/theater.html.twig', $data->all());
    }
}
