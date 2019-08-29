<?php
/**
 * AuthorizationController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Controller;

use Slim\Http\Uri as HttpUri;

use GuzzleHttp\Exception\ClientException;

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
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeLogin($request, $response, $args)
    {
        $this->logger->info('Login params', $request->getParams());

        $state = $request->getParam('state');
        $code = $request->getParam('code');

        // Authorization URLエラーページからの戻りはパラメータ無し
        if (empty($state) && empty($code)) {
            $this->logger->info('Authorization URL error.');
            $this->redirect($this->router->pathFor('homepage'));
        }

        if (empty($state)
            || $state !== $this->am->getAuthorizationState()
        ) {
            // TODO: エラーページ
            throw new \RuntimeException('Invalid state.');
        }

        $this->am->clearAuthorizationState();

        $uri = HttpUri::createFromEnvironment($this->environment);
        $redirectUri = $this->router->fullUrlFor($uri, 'login');

        try {
            $accessToken = $this->am->requestAccessToken($code, $redirectUri);
        } catch (ClientException $e) {
            // TODO: エラーページ
            throw $e;
        }

        $this->am->login($accessToken);

        // TODO: 劇場ページへ
        $this->redirect($this->router->pathFor('homepage'));
    }

    /**
     * logout action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeLogout($request, $response, $args)
    {
        $this->am->logout();

        // TODO: 劇場ページへ
        $this->redirect($this->router->pathFor('homepage'));
    }
}
