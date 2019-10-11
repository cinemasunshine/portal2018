<?php
/**
 * NewsResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder;

use Slim\Collection;
use Slim\Http\Response;

/**
 * News responder
 */
class NewsResponder extends BaseResponder
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
        return $this->view->render($response, 'news/list.html.twig', $data->all());
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
        return $this->view->render($response, 'news/show.html.twig', $data->all());
    }
}
