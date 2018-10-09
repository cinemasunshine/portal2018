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
    
    /**
     * mvtk
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function mvtk(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/mvtk.html.twig', $data->all());
    }
    
    /**
     * official app
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function officialApp(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/official_app.html.twig', $data->all());
    }
    
    /**
     * privacy
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function privacy(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/privacy.html.twig', $data->all());
    }
    
    /**
     * question
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function question(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/question.html.twig', $data->all());
    }
    
    /**
     * send completely
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function sendCompletely(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/send_completely.html.twig', $data->all());
    }
    
    /**
     * terms of service
     *
     * @param Response   $response
     * @param Collection $data
     * @return Response
     */
    public function termsOfService(Response $response, Collection $data)
    {
        return $this->view->render($response, 'about/terms_of_service.html.twig', $data->all());
    }
}
