<?php

declare(strict_types=1);

namespace App\Resource;

class MetaTag
{
    protected string $title;

    protected string $description;

    protected string $keywords;

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
