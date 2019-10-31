<?php
/**
 * AuthorizationResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder\API;

use Slim\Collection;
use Slim\Http\Response;

/**
 * Authorization responder
 */
class AuthorizationResponder extends BaseResponder
{
    /**
     * token
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function token(Response $response, Collection $data)
    {
        return $response->withJson($data->all());
    }

    /**
     * bad request
     *
     * @param Response   $response
     * @param Collection $data
     * @return void
     */
    public function badRequest(Response $response, Collection $data)
    {
        return $response->withJson($data->all(), 400);
    }
}
