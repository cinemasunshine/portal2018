<?php

/**
 * TheaterExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\ORM\Entity\Theater;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Theater twig extension class
 */
class TheaterExtension extends AbstractExtension
{
    /**
     * metaタグkeywords
     *
     * 添字は劇場ID。
     *
     * @var array
     */
    protected $metaKeywords = [
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
     * get filters
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new TwigFilter('theater_name_ja', [$this, 'filterNameJa'], [ 'is_safe' => ['all'] ]),
            new TwigFilter('theater_name_ja2', [$this, 'filterNameJa2'], [ 'is_safe' => ['all'] ]),
        ];
    }

    /**
     * filter name_ja
     *
     * @param string $nameJa
     * @return string
     */
    public function filterNameJa(string $nameJa): string
    {
        if ($nameJa === 'グランドシネマサンシャイン') {
            $br = '<br class="tn_br_gdcs">';
            $filtered = 'グランド' . $br . 'シネマサンシャイン';
        } else {
            $filtered = $nameJa;
        }

        return $filtered;
    }

    /**
     * filter name_ja
     *
     * @param string $nameJa
     * @return string
     */
    public function filterNameJa2(string $nameJa): string
    {
        if ($nameJa === 'グランドシネマサンシャイン') {
            $br = '<br class="tn_br_gdcs">';
            $filtered = 'グランド' . $br . 'シネマ' . $br . 'サンシャイン';
        } else {
            $filtered = $nameJa;
        }

        return $filtered;
    }

    /**
     * get functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('theater_area', [$this, 'theaterArea']),
            new TwigFunction('theater_meta_keywords', [$this, 'getMetaKeywords']),
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
     * @return string|null
     */
    public function getMetaKeywords(Theater $theater)
    {
        return $this->metaKeywords[$theater->getId()] ?? null;
    }
}
