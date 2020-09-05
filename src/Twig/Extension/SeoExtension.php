<?php

/**
 * SeoExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Resource\MetaTag;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * SEO twig extension class
 */
class SeoExtension extends AbstractExtension
{
    /** @var MetaTag[] */
    protected $metas;

    /**
     * @param string $file
     */
    public function __construct(string $file)
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException('File does not exist.');
        }

        $this->metas = $this->loadMetas($file);
    }

    /**
     * @param string $file
     * @return MetaTag[]
     */
    protected function loadMetas(string $file): array
    {
        $json = json_decode(file_get_contents($file), true);
        $metas = [];

        foreach ($json as $key => $row) {
            $metas[$key] = new MetaTag($row['title'], $row['description'], $row['keywords']);
        }

        return $metas;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('meta_title', [$this, 'getTilte'], [ 'is_safe' => ['html'] ]),
            new TwigFunction('meta_description', [$this, 'getDescription'], [ 'is_safe' => ['html'] ]),
            new TwigFunction('meta_keywords', [$this, 'getKeywords'], [ 'is_safe' => ['html'] ]),
        ];
    }

    /**
     * @param string $key
     * @return string
     */
    public function getTilte(string $key): string
    {
        return isset($this->metas[$key]) ? $this->metas[$key]->getTitle() : '';
    }

    /**
     * @param string $key
     * @return string
     */
    public function getDescription(string $key): string
    {
        return isset($this->metas[$key]) ? $this->metas[$key]->getDescription() : '';
    }

    /**
     * @param string $key
     * @return string
     */
    public function getKeywords(string $key): string
    {
        return isset($this->metas[$key]) ? $this->metas[$key]->getKeywords() : '';
    }
}
