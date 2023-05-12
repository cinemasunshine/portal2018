<?php

declare(strict_types=1);

namespace App\Controller\Authorization;

use App\User\User;
use Slim\Http\Request;
use Slim\Http\Response;
use Throwable;

class MembershipController extends BaseController
{
    /**
     * @param array<string, mixed> $args
     */
    public function executeLogin(Request $request, Response $response, array $args): Response
    {
        $this->logger->info('Login params', $request->getParams());

        $state = $request->getParam('state');
        $code  = $request->getParam('code');

        if (empty($state) && empty($code)) {
            $this->logger->info('Authorization URL error.');
            $this->redirect($this->router->pathFor('homepage'));
        }

        try {
            $token = $this->membershipAuth->requestToken($code);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());

            return $this->renderError($response);
        }

        $this->um->login($token, User::SERVICE_TYPE_MEMBERSHIP);

        $redirectUrl = $this->getRedirectUrlOnSuccessful();
        $this->redirect($redirectUrl);
    }

    protected function renderError(Response $response): Response
    {
        return $this->render($response, 'authorization/error.html.twig');
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeLogout(Request $request, Response $response, array $args): void
    {
        $this->um->logout();

        $redirectUrl = $this->getRedirectUrlOnSuccessful();
        $this->redirect($redirectUrl);
    }

    private function getRedirectUrlOnSuccessful(): string
    {
        $session = $this->sm->getContainer();

        if (isset($session['viewed_theater'])) {
            return $this->router->pathFor(
                'theater',
                ['name' => $session['viewed_theater']]
            );
        }

        return $this->router->pathFor('homepage');
    }
}
