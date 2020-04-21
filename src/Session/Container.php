<?php

/**
 * Container.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Session;

use Laminas\Session\Container as Base;

/**
 * Session Container class
 */
class Container extends Base
{
    /**
     * clear
     *
     * @return void
     */
    public function clear()
    {
        $this->getStorage()->clear($this->getName());
    }
}
