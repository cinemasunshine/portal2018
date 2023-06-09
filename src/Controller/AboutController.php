<?php

declare(strict_types=1);

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class AboutController extends GeneralController
{
    /**
     * company action
     *
     * @param array<string, mixed> $args
     */
    public function executeCompany(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/company.html.twig');
    }

    /**
     * mail magazine action
     *
     * @param array<string, mixed> $args
     */
    public function executeMailMagazine(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/mail_magazine.html.twig');
    }

    /**
     * mvtk action
     *
     * @param array<string, mixed> $args
     */
    public function executeMvtk(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/mvtk.html.twig');
    }

    /**
     * official app action
     *
     * @param array<string, mixed> $args
     */
    public function executeOfficialApp(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/official_app.html.twig');
    }

    /**
     * online ticket action
     *
     * @param array<string, mixed> $args
     */
    public function executeOnlineTicket(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/online_ticket.html.twig');
    }

    /**
     * privacy action
     *
     * @param array<string, mixed> $args
     */
    public function executePrivacy(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/privacy.html.twig');
    }

    /**
     * reward action
     *
     * @param array<string, mixed> $args
     */
    public function executeReward(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/reward.html.twig');
    }

    /**
     * reward action
     *
     * @param array<string, mixed> $args
     */
    public function executeReward(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/cs_reward.html.twig');
    }

    /**
     * question action
     *
     * @param array<string, mixed> $args
     */
    public function executeQuestion(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/question.html.twig');
    }

    /**
     * sitemap action
     *
     * @param array<string, mixed> $args
     */
    public function executeSitemap(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/sitemap.html.twig', [
            'theaters' => $this->findTheaters(),
        ]);
    }

    /**
     * special ticket action
     *
     * @param array<string, mixed> $args
     */
    public function executeSpecialTicket(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/special_ticket.html.twig');
    }

    /**
     * specific quotient action
     *
     * @param array<string, mixed> $args
     */
    public function executeSpecificQuotient(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/specific_quotient.html.twig');
    }

    /**
     * terms of service action
     *
     * @param array<string, mixed> $args
     */
    public function executeTermsOfService(Request $request, Response $response, array $args): Response
    {
        return $this->render($response, 'about/terms_of_service.html.twig');
    }
}
