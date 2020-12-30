<?php

declare(strict_types=1);

namespace App\Controller;

use GuzzleHttp\Exception\BadResponseException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri as HttpUri;

/**
 * Authorization controller
 */
class AuthorizationController extends BaseController
{
    /**
     * login action
     *
     * リクエストエラーは認可サーバで処理される。
     * ただしそこからパラメータ無しで戻るリンクがある。
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeLogin(Request $request, Response $response, array $args)
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

        $this->am->clearAuthorizationState();

        $uri         = HttpUri::createFromEnvironment($this->environment);
        $redirectUri = $this->router->fullUrlFor($uri, 'login');

        try {
            $token = $this->am->requestToken($code, $redirectUri);
        } catch (BadResponseException $e) {
            $this->logger->error($e->getMessage());

            return $this->renderError($response);
        }

        $this->um->login($token);

        // redirect
        $redirectPath = $this->router->pathFor('homepage');
        $session      = $this->sm->getContainer();

        if (isset($session['viewed_theater'])) {
            $redirectPath = $this->router->pathFor(
                'theater',
                ['name' => $session['viewed_theater']]
            );
        }

        $this->redirect($redirectPath);
    }

    /**
     * @param Response $response
     * @return Response
     */
    protected function renderError(Response $response)
    {
        return $this->render($response, 'authorization/error.html.twig');
    }

    /**
     * logout action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return void
     */
    public function executeLogout(Request $request, Response $response, array $args)
    {
        $this->um->logout();

        // redirect
        $redirectPath = $this->router->pathFor('homepage');
        $session      = $this->sm->getContainer();

        if (isset($session['viewed_theater'])) {
            $redirectPath = $this->router->pathFor(
                'theater',
                ['name' => $session['viewed_theater']]
            );
        }

        $this->redirect($redirectPath);
    }
}
