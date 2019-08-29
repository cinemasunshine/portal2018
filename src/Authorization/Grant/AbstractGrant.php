<?php
/**
 * AbstractGrant.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Authorization\Grant;

/**
 * Abstract Grant class
 */
abstract class AbstractGrant
{
    /**
     * create unique string
     *
     * @param string $name
     * @param string|null $salt
     * @return string
     */
    protected function createUniqueStr(string $name, ?string $salt = 'salt'): string
    {
        return md5($salt . uniqid((string) random_int(1, 99999), true) . $name);
    }
}
