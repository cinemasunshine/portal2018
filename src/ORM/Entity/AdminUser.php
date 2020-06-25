<?php

/**
 * AdminUser.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\ORM\Entity;

use Cinemasunshine\ORM\Entity\AdminUser as BaseAdminUser;
use Cinemasunshine\ORM\Entity\Theater as BaseTheater;
use Doctrine\ORM\Mapping as ORM;

/**
 * AdminUser entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="admin_user", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class AdminUser extends BaseAdminUser
{
    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function __construct()
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setName(string $name)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setDisplayName(string $displayName)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setPassword(string $password)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setGroup(int $group)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setTheater(?BaseTheater $theater)
    {
        throw new \LogicException('Not allowed.');
    }
}
