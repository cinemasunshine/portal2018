<?php

/**
 * TheaterResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\Responder;

use Psr\Http\Message\ResponseInterface;
use Slim\Collection;
use Slim\Http\Response;

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
     * @return ResponseInterface
     */
    public function index(Response $response, Collection $data)
    {
        return $this->view->render($response, 'theater/index/index.html.twig', $data->all());
    }

    /**
     * closed
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function closed(Response $response, Collection $data)
    {
        return $this->view->render($response, 'theater/index/closed.html.twig', $data->all());
    }

    /**
     * access
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function access(Response $response, Collection $data)
    {
        return $this->view->render($response, 'theater/access.html.twig', $data->all());
    }

    /**
     * admission
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function admission(Response $response, Collection $data)
    {
        return $this->view->render($response, 'theater/admission.html.twig', $data->all());
    }

    /**
     * advance ticket
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function advanceTicket(Response $response, Collection $data)
    {
        return $this->view->render($response, 'theater/advance_ticket.html.twig', $data->all());
    }

    /**
     * concession
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function concession(Response $response, Collection $data)
    {
        return $this->view->render($response, 'theater/concession.html.twig', $data->all());
    }

    /**
     * floor guide
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function floorGuide(Response $response, Collection $data)
    {
        return $this->view->render($response, 'theater/floor_guide.html.twig', $data->all());
    }

    /**
     * news list
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function newsList(Response $response, Collection $data)
    {
        return $this->view->render($response, 'theater/news/list.html.twig', $data->all());
    }

    /**
     * news show
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function newsShow(Response $response, Collection $data)
    {
        return $this->view->render($response, 'theater/news/show.html.twig', $data->all());
    }
}
