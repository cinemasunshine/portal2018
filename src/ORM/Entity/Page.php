<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\Page as BasePage;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * Page entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="App\ORM\Repository\PageRepository")
 * @ORM\Table(name="page", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Page extends BasePage
{
    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function __construct()
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setName(string $name)
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setNameJa(string $nameJa)
    {
        throw new LogicException('Not allowed.');
    }
}
