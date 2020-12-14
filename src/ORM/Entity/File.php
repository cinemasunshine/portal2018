<?php

/**
 * File.php
 */

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\File as BaseFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * File entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="file", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class File extends BaseFile
{
    /**
     * blob container name
     *
     * @var string
     */
    protected static $blobContainer = 'file';

    /**
     * get blob container
     *
     * @return string
     */
    public static function getBlobContainer()
    {
        return self::$blobContainer;
    }

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
    public function setOriginalName(string $originalName)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setMimeType(string $mimeType)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setSize(int $size)
    {
        throw new \LogicException('Not allowed.');
    }
}
