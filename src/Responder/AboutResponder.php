<?php

/**
 * AboutResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\Responder;

use Psr\Http\Message\ResponseInterface;
use Slim\Collection;
use Slim\Http\Response;

/**
 * About responder
 */
class AboutResponder extends BaseResponder
{
    /**
     * company
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function company(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/company.html.twig', $data->all());
    }

    /**
     * mail magazine
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function mailMagazine(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/mail_magazine.html.twig', $data->all());
    }

    /**
     * mvtk
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function mvtk(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/mvtk.html.twig', $data->all());
    }

    /**
     * official app
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function officialApp(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/official_app.html.twig', $data->all());
    }

    /**
     * online ticket
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function onlineTicket(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/online_ticket.html.twig', $data->all());
    }

    /**
     * privacy
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function privacy(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/privacy.html.twig', $data->all());
    }

    /**
     * question
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function question(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/question.html.twig', $data->all());
    }

    /**
     * sitemap
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function sitemap(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/sitemap.html.twig', $data->all());
    }

    /**
     * special ticket
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function specialTicket(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/special_ticket.html.twig', $data->all());
    }

    /**
     * specific quotient
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function specificQuotient(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/specific_quotient.html.twig', $data->all());
    }

    /**
     * terms of service
     *
     * @param Response   $response
     * @param Collection $data
     * @return ResponseInterface
     */
    public function termsOfService(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/terms_of_service.html.twig', $data->all());
    }
}
