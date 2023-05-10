<?php

declare(strict_types=1);

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri as HttpUri;
use Throwable;

class AuthorizationController extends BaseController
{
    /**
     * login action
     *
     * リクエストエラーは認可サーバで処理される。
     * ただしそこからパラメータ無しで戻るリンクがある。
     *
     * @param array<string, mixed> $args
     */
    public function executeLogin(Request $request, Response $response, array $args): Response
    {
        $this->logger->info('Login params', $request->getParams());

        $state = $request->getParam('state');
        $code  = $request->getParam('code');

        // Authorization URLエラーページからの戻りはパラメータ無し
        if (empty($state) && empty($code)) {
            $this->logger->info('Authorization URL error.');
            $this->redirect($this->router->pathFor('homepage'));
        }

        if (
            empty($state)
            || $state !== $this->am->getAuthorizationState()
        ) {
            $this->logger->info('Invalid state.');

            return $this->renderError($response);
        }

        $uri         = HttpUri::createFromEnvironment($this->environment);
        $redirectUri = $this->router->fullUrlFor($uri, 'login');

        try {
            $token = $this->am->requestToken($code, $redirectUri);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());

            return $this->renderError($response);
        }

        $this->um->login($token);

        $redirectUrl = $this->getRedirectUrlOnSuccessful();
        $this->redirect($redirectUrl);
    }

    protected function renderError(Response $response): Response
    {
        return $this->render($response, 'authorization/error.html.twig');
    }

    /**
     * logout action
     *
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
