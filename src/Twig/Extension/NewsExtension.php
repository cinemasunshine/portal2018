<?php
/**
 * NewsExtension.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\ORM\Entity\News;

/**
 * News twig extension class
 */
class NewsExtension extends \Twig_Extension
{
    /** @var array */
    protected $categoryLabels = [
        News::CATEGORY_NEWS  => 'ニュース',
        News::CATEGORY_INFO  => 'インフォメーション',
        News::CATEGORY_IMAX  => 'IMAX',
        News::CATEGORY_4DX   => '4DX',
        News::CATEGORY_EVENT => 'ライブビューイング・イベント',
    ];
    
    /** @var array */
    protected $categoryLabelClasses = [
        News::CATEGORY_NEWS  => 'list-type-news',
        News::CATEGORY_INFO  => 'ist-type-information text-white',
        News::CATEGORY_IMAX  => 'list-type-imax text-white',
        News::CATEGORY_4DX   => 'list-type-4dx text-white',
        News::CATEGORY_EVENT => 'list-type-event text-white',
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
            new \Twig_Function('news_category_label', [$this, 'getCategoryLabel']),
            new \Twig_Function('news_category_label_class', [$this, 'getCategoryLabelClass']),
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