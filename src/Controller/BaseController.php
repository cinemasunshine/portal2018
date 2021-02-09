<?php

declare(strict_types=1);

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

abstract class BaseController extends AbstractController
{
    /**
     * @param array<string, mixed> $args
     */
    protected function preExecute(Request $request, Response $response, array $args): void
    {
    }

    /**
     * @param array<string, mixed> $args
     */
    protected function postExecute(Request $request, Response $response, array $args): void
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function render(Response $response, string $template, array $data = []): Response
    {
        return $this->view->render($response, $template, $data);
    }
}
