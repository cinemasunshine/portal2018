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
use Cinemasunshine\Portal\Responder\AbstractResponder;
use Cinemasunshine\Portal\Responder\API\ResponderFactory;

/**
 * Base controller
 */
abstract class BaseController extends AbstractController
{
    /**
     * {@inheritDoc}
     */
    protected function preExecute($request, $response, $args): void
    {
    }

    /**
     * {@inheritDoc}
     */
    protected function postExecute($request, $response, $args): void
    {
    }

    /**
     * get responder
     *
     * @return AbstractResponder
     */
    protected function getResponder(): AbstractResponder
    {
        $path = explode('\\', get_class($this));
        $name = str_replace('Controller', '', array_pop($path));

        return ResponderFactory::factory($name);
    }
}
