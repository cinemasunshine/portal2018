<?php

/**
 * NotFound.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Application\Handlers;

use Slim\Container;
use Slim\Handlers\NotFound as BaseHandler;

/**
 * NotFound handler
 */
class NotFound extends BaseHandler
{
    /** @var Container */
    protected $container;
    
    /**
     * construct
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
