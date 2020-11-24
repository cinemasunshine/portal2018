<?php

/**
 * BaseController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\Controller\API;

use App\Controller\AbstractController;
use App\Responder\AbstractResponder;
use App\Responder\API\ResponderFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;

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
        $path = explode('\\', static::class);
        $name = str_replace('Controller', '', array_pop($path));

        return ResponderFactory::factory($name);
    }
}
