<?php
/**
 * TheaterExtension.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\ORM\Entity\Theater;

/**
 * Theater twig extension class
 */
class TheaterExtension extends \Twig_Extension
{
    /**
     * metaタグkeywords
     * 
     * 添字は劇場ID。
     *
     * @var array
     */
    protected $metaKeywords = [
        1  => '東京,池袋',
        2  => '東京,平和島',
        6  => '静岡,沼津,4DX,bivi',
        7  => '徳島,北島,フジグラン',
        8  => '愛媛,松山,衣山,IMAX,パルティフジ',
        9  => '愛媛,松山,大街道',
        12 => '愛媛,東温,野田,フジグラン,重信',
        13 => '茨城,土浦,イオン,IMAX,IMAX3D',
        14 => '石川,かほく,イオン',
        15 => '愛媛,伊予,松前,エミフルMASAKI,4DX',
        16 => '奈良,大和郡山,イオン,IMAX,4DX',
        17 => '山口,下関,シーモール',
        18 => '鹿児島,姶良,イオン,4DX',
        19 => '千葉,ユーカリが丘,ユーカリプラザ',
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
            new \Twig_Function('theater_area', [$this, 'theaterArea']),
            new \Twig_Function('theater_meta_keywords', [$this, 'getMetaKeywords']),
        ];
    }
    
    /**
     * return theater area label
     *
     * @param int $area
     * @return string|null
     */
    public function theaterArea(int $area)
    {
        $areas = Theater::getAreas();
        
        return $areas[$area] ?? null;
    }
    
    /**
     * metaタグkeywordを取得
     *
     * @param Theater $theater
     * @return void
     */
    public function getMetaKeywords(Theater $theater)
    {
        return $this->metaKeywords[$theater->getId()] ?? null;
    }
}