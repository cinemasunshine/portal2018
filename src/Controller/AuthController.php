<?php
/**
 * AuthController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Controller;

/**
 * Auth controller
 */
class AuthController extends BaseController
{
    /**
     * login action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeLogin($request, $response, $args)
    {
        // todo
        echo 'login';
        var_dump($request->getParams());
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
