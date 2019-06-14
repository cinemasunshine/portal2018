<?php
/**
 * FourdxWithScreenXResponder.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder;

use Slim\Collection;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * FourdxWithScreenX responder
 *
 * 4DX with ScreenX
 */
class FourdxWithScreenXResponder extends BaseResponder
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
        return $this->view->render($response, '4dx_with_screenx/index.html.twig', $data->all());
    }
    
    /**
     * about
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function about(Response $response, Collection $data)
    {
        return $this->view->render($response, '4dx_with_screenx/about.html.twig', $data->all());
    }
    
    /**
     * schedule list
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function scheduleList(Response $response, Collection $data)
    {
        return $this->view->render($response, '4dx_with_screenx/schedule/list.html.twig', $data->all());
    }
}
