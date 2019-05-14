<?php
/**
 * ScheduleResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder;

use Slim\Collection;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Schedule responder
 */
class ScheduleResponder extends BaseResponder
{
    /**
     * list
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function list(Response $response, Collection $data)
    {
        return $this->view->render($response, 'schedule/list.html.twig', $data->all());
    }
    
    /**
     * show
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function show(Response $response, Collection $data)
    {
        return $this->view->render($response, 'schedule/show.html.twig', $data->all());
    }
}
