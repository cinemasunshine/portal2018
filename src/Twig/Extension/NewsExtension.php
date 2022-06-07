<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\ORM\Entity\News;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * News twig extension class
 */
class NewsExtension extends AbstractExtension
{
    /** @var array<int, string> */
    protected array $categoryLabels = [
        News::CATEGORY_NEWS       => 'ニュース',
        News::CATEGORY_INFO       => 'インフォメーション',
        News::CATEGORY_IMAX       => 'IMAX',
        News::CATEGORY_4DX        => '4DX',
        News::CATEGORY_SCREENX    => 'ScreenX',
        News::CATEGORY_EVENT      => 'ライブビューイング・イベント',
        News::CATEGORY_4DX_SCREEN => '4DX SCREEN',
    ];

    /** @var array<int, string> */
    protected array $categoryLabelClasses = [
        News::CATEGORY_NEWS       => 'list-type-news',
        News::CATEGORY_INFO       => 'list-type-information',
        News::CATEGORY_IMAX       => 'list-type-imax',
        News::CATEGORY_4DX        => 'list-type-4dx',
        News::CATEGORY_SCREENX    => 'list-type-scx',
        News::CATEGORY_EVENT      => 'list-type-event',
        News::CATEGORY_4DX_SCREEN => 'list-type-4dxwscx',
    ];

    public function __construct()
    {
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('news_category_label', [$this, 'getCategoryLabel']),
            new TwigFunction('news_category_label_class', [$this, 'getCategoryLabelClass']),
        ];
    }

    public function getCategoryLabel(int $category): ?string
    {
        return $this->categoryLabels[$category] ?? null;
    }

    public function getCategoryLabelClass(int $category): ?string
    {
        return $this->categoryLabelClasses[$category] ?? null;
    }
}
