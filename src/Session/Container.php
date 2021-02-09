<?php

declare(strict_types=1);

namespace App\Session;

use Laminas\Session\Container as Base;

/**
 * Session Container class
 */
class Container extends Base
{
    public function clear(): void
    {
        $this->getStorage()->clear($this->getName());
    }
}
