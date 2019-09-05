<?php
/**
 * NewsExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\ORM\Entity\News;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * News twig extension class
 */
class NewsExtension extends AbstractExtension
{
    /** @var array */
    protected $categoryLabels = [
        News::CATEGORY_NEWS             => 'ニュース',
        News::CATEGORY_INFO             => 'インフォメーション',
        News::CATEGORY_IMAX             => 'IMAX',
        News::CATEGORY_4DX              => '4DX',
        News::CATEGORY_SCREENX          => 'ScreenX',
        News::CATEGORY_EVENT            => 'ライブビューイング・イベント',
        News::CATEGORY_4DX_WITH_SCREENX => '4DX with ScreenX',
    ];

    /** @var array */
    protected $categoryLabelClasses = [
        News::CATEGORY_NEWS             => 'list-type-news',
        News::CATEGORY_INFO             => 'list-type-information',
        News::CATEGORY_IMAX             => 'list-type-imax',
        News::CATEGORY_4DX              => 'list-type-4dx',
        News::CATEGORY_SCREENX          => 'list-type-scx',
        News::CATEGORY_EVENT            => 'list-type-event',
        News::CATEGORY_4DX_WITH_SCREENX => 'list-type-4dxwscx',
    ];

    /**
     * construct
     */
    public function __construct()
    {
    }

    /**
     * get functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('news_category_label', [$this, 'getCategoryLabel']),
            new TwigFunction('news_category_label_class', [$this, 'getCategoryLabelClass']),
        ];
    }

    /**
     * return category label
     *
     * @param int $category
     * @return string|null
     */
    public function getCategoryLabel(int $category)
    {
        return $this->categoryLabels[$category] ?? null;
    }

    /**
     * return category label class
     *
     * @param int $category
     * @return void
     */
    public function getCategoryLabelClass(int $category)
    {
        return $this->categoryLabelClasses[$category] ?? null;
    }
}
