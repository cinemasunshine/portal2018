<?php
/**
 * ScheduleResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder\API;

use Slim\Collection;
use Slim\Http\Response;

/**
 * Schedule responder
 */
class ScheduleResponder extends BaseResponder
{
    /**
     * index
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function index(Response $response, Collection $data)
    {
        return $response->withJson($data->all());
    }

    /**
     * date
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function date(Response $response, Collection $data)
    {
        return $response->withJson($data->all());
    }
}
