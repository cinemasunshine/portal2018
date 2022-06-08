<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Resource\MetaTag;
use InvalidArgumentException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SeoExtension extends AbstractExtension
{
    /** @var MetaTag[] */
    protected array $metas;

    public function __construct(string $file)
    {
        $this->metas = $this->loadMetas($file);
    }

    /**
     * @return MetaTag[]
     */
    protected function loadMetas(string $file): array
    {
        if (! file_exists($file)) {
            throw new InvalidArgumentException('File does not exist.');
        }

        $json  = json_decode(file_get_contents($file), true);
        $metas = [];

        foreach ($json as $key => $row) {
            $metas[$key] = new MetaTag($row['title'], $row['description'], $row['keywords']);
        }

        return $metas;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('meta_title', [$this, 'getTilte'], [ 'is_safe' => ['html'] ]),
            new TwigFunction('meta_description', [$this, 'getDescription'], [ 'is_safe' => ['html'] ]),
            new TwigFunction('meta_keywords', [$this, 'getKeywords'], [ 'is_safe' => ['html'] ]),
        ];
    }

    public function getTilte(string $key): string
    {
        return isset($this->metas[$key]) ? $this->metas[$key]->getTitle() : '';
    }

    public function getDescription(string $key): string
    {
        return isset($this->metas[$key]) ? $this->metas[$key]->getDescription() : '';
    }

    public function getKeywords(string $key): string
    {
        return isset($this->metas[$key]) ? $this->metas[$key]->getKeywords() : '';
    }
}
