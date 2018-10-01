<?php
/**
 * AboutResponder.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Responder;

use Slim\Collection;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * About responder
 */
class AboutResponder extends BaseResponder
{
    /**
     * company
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function company(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/company.html.twig', $data->all());
    }
}
