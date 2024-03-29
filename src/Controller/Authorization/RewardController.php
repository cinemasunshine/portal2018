<?php

declare(strict_types=1);

namespace App\Controller\Authorization;

use App\User\Provider\RewardProvider as RewardUserProvider;
use Slim\Http\Request;
use Slim\Http\Response;
use Throwable;

class RewardController extends BaseController
{
    private function getRewardUserProvider(): RewardUserProvider
    {
        return $this->um->getRewardProvider();
    }

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
            || $state !== $this->rewardAuth->getAuthorizationState()
        ) {
            $this->logger->info('Invalid state.');

            return $this->renderError($response);
        }

        try {
            $token = $this->rewardAuth->requestToken($code);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());

            return $this->renderError($response);
        }

        $this->getRewardUserProvider()->login($token);

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
        $this->getRewardUserProvider()->logout();

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
