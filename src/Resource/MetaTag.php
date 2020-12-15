<?php

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

    public function __construct(string $title, string $description, string $keywords)
    {
        $this->title       = $title;
        $this->description = $description;
        $this->keywords    = $keywords;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }
}
