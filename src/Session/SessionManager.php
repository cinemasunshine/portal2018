<?php

/**
 * SessionManager.php
 */

namespace App\Session;

use App\Session\Container;
use Laminas\Session\Config\ConfigInterface;
use Laminas\Session\SessionManager as Base;

/**
 * SessionManager class
 */
class SessionManager extends Base
{
    /** @var Container[] */
    protected $containers = [];

    /**
     * construct
     *
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        parent::__construct($config);

        Container::setDefaultManager($this);
    }

    /**
     * return session container
     *
     * @param string $name
     * @return Container
     */
    public function getContainer(string $name = 'default')
    {
        if (! isset($this->containers[$name])) {
            $this->containers[$name] = new Container($name);
        }

        return $this->containers[$name];
    }
}
