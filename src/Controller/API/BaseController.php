<?php
/**
 * BaseController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller\API;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;

use Cinemasunshine\Portal\Controller\AbstractController;
use Cinemasunshine\Portal\Responder;
use Cinemasunshine\Portal\Responder\API as ApiResponder;

/**
 * Base controller
 */
abstract class BaseController extends AbstractController
{
    /**
     * {@inheritDoc}
     */
    protected function preExecute($request, $response, $args) : void
    {
    }

    /**
     * {@inheritDoc}
     */
    protected function postExecute($request, $response, $args) : void
    {
    }

    /**
     * get responder
     *
     * @return Responder\AbstractResponder
     */
    protected function getResponder() : Responder\AbstractResponder
    {
        $path = explode('\\', get_class($this));
        $container = str_replace('Controller', '', array_pop($path));
        $responder = ApiResponder::class . '\\' . $container . 'Responder';

        return new $responder($this->view);
    }
}
