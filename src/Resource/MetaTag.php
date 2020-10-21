<?php

/**
 * MetaTag.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace App\Resource;

class MetaTag
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $description;

    /** @var string */
    protected $keywords;

    /**
     * @param string $title
     * @param string $description
     * @param string $keywords
     */
    public function __construct(string $title, string $description, string $keywords)
    {
        $this->title       = $title;
        $this->description = $description;
        $this->keywords    = $keywords;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }
}
