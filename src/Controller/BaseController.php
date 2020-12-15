<?php

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

abstract class BaseController extends AbstractController
{
    protected function preExecute(Request $request, Response $response, array $args): void
    {
    }

    protected function postExecute(Request $request, Response $response, array $args): void
    {
    }

    /**
     * @param Response $response
     * @param string   $template
     * @param array    $data
     * @return Response
     */
    protected function render(Response $response, string $template, array $data = []): Response
    {
        return $this->view->render($response, $template, $data);
    }
}
