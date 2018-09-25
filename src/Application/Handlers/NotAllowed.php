<?php
/**
 * NotAllowed.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Application\Handlers;

use Slim\Container;
use Slim\Handlers\NotAllowed as BaseHandler;

/**
 * NotAllowed handler
 */
class NotAllowed extends BaseHandler
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