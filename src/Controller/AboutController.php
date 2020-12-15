<?php

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class AboutController extends GeneralController
{
    /**
     * company action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeCompany(Request $request, Response $response, array $args)
    {
        return $this->render($response, 'about/company.html.twig');
    }

    /**
     * mail magazine action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeMailMagazine(Request $request, Response $response, array $args)
    {
        return $this->render($response, 'about/mail_magazine.html.twig');
    }

    /**
     * mvtk action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeMvtk(Request $request, Response $response, array $args)
    {
        return $this->render($response, 'about/mvtk.html.twig');
    }

    /**
     * official app action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeOfficialApp(Request $request, Response $response, array $args)
    {
        return $this->render($response, 'about/official_app.html.twig');
    }

    /**
     * online ticket action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeOnlineTicket(Request $request, Response $response, array $args)
    {
        return $this->render($response, 'about/online_ticket.html.twig');
    }

    /**
     * privacy action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executePrivacy(Request $request, Response $response, array $args)
    {
        return $this->render($response, 'about/privacy.html.twig');
    }

    /**
     * question action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeQuestion(Request $request, Response $response, array $args)
    {
        return $this->render($response, 'about/question.html.twig');
    }

    /**
     * sitemap action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeSitemap(Request $request, Response $response, array $args)
    {
        return $this->render($response, 'about/sitemap.html.twig', [
            'theaters' => $this->getTheaters(),
        ]);
    }

    /**
     * special ticket action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeSpecialTicket(Request $request, Response $response, $args)
    {
        return $this->render($response, 'about/special_ticket.html.twig');
    }

    /**
     * specific quotient action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeSpecificQuotient(Request $request, Response $response, $args)
    {
        return $this->render($response, 'about/specific_quotient.html.twig');
    }

    /**
     * terms of service action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeTermsOfService(Request $request, Response $response, $args)
    {
        return $this->render($response, 'about/terms_of_service.html.twig');
    }
}
