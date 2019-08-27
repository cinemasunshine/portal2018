<?php
/**
 * AuthController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Controller;

use Slim\Http\Uri as HttpUri;

use GuzzleHttp\Exception\ClientException;

/**
 * Auth controller
 */
class AuthController extends BaseController
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

        /**
         * stateの検証
         * リクエストエラーからの戻りのケースもここに含めておく（適宜変更すること）
         * TODO: stateの一致検証。セッション（もしくはフラッシュ）に入れた値と一致確認。
         */
        if (empty($state)) {
            $this->logger->info('Invalid state');
            $this->redirect($this->router->pathFor('homepage'));
        }

        /**
         * リクエストエラーからの戻りはstateの検証で対応。
         * codeのみ空は想定しない。
         * （仮にあってもアクセストークン取得でエラーになるはずなので、ひとまずそれで良しとする）
         */
        $code = $request->getParam('code');


        $uri = HttpUri::createFromEnvironment($this->environment);
        $redirectUri = $this->router->fullUrlFor($uri, 'login');

        try {
            $accessToken = $this->am->requestAccessToken($code, $redirectUri);
        } catch (ClientException $e) {
            // TODO: エラーページ
            throw $e;
        }

        // todo
        var_dump($accessToken);
        exit;
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
        // todo
        echo 'loout';
        var_dump($request->getParams());
        exit;
    }
}
