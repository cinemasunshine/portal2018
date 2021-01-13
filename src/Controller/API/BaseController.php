<?php

namespace App\Controller\API;

use App\Controller\AbstractController;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Base controller
 */
abstract class BaseController extends AbstractController
{
    protected function preExecute(Request $request, Response $response, array $args): void
    {
    }

    protected function postExecute(Request $request, Response $response, array $args): void
    {
    }
}
