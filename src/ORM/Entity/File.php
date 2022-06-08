<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\File as BaseFile;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

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
     */
    protected static string $blobContainer = 'file';

    /**
     * Return blob container name
     */
    public static function getBlobContainer(): string
    {
        return self::$blobContainer;
    }

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
    public function setName(string $name): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setOriginalName(string $originalName): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setMimeType(string $mimeType): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setSize(int $size): void
    {
        throw new LogicException('Not allowed.');
    }
}
