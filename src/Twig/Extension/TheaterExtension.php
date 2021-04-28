<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\ORM\Entity\Theater;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TheaterExtension extends AbstractExtension
{
    /**
     * metaタグkeywords
     *
     * 添字は劇場ID。
     *
     * @var array<int, string>
     */
    protected $metaKeywords = [
        2  => '東京,平和島',
        6  => '静岡,沼津,4DX,bivi',
        7  => '徳島,北島,フジグラン',
        8  => '愛媛,松山,衣山,IMAX,パルティフジ',
        12 => '愛媛,東温,野田,フジグラン,重信',
        13 => '茨城,土浦,イオン,IMAX,IMAX3D',
        14 => '石川,かほく,イオン',
        15 => '愛媛,伊予,松前,エミフルMASAKI,4DX',
        16 => '奈良,大和郡山,イオン,IMAX,4DX',
        17 => '山口,下関,シーモール',
        18 => '鹿児島,姶良,イオン,4DX',
        19 => '千葉,ユーカリが丘,ユーカリプラザ',
    ];

    public function __construct()
    {
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('theater_name_ja', [$this, 'filterNameJa'], [ 'is_safe' => ['all'] ]),
            new TwigFilter('theater_name_ja2', [$this, 'filterNameJa2'], [ 'is_safe' => ['all'] ]),
        ];
    }

    public function filterNameJa(Theater $theater): string
    {
        $nameJa = $theater->getNameJa();
        $filtered = $nameJa;

        if ($theater->getId() === 20) {
            $br = '<br class="tn_br_gdcs">';

            $filtered
                = mb_substr($nameJa, 0, 4)
                . $br
                . mb_substr($nameJa, 4);
        }

        return $filtered;
    }

    public function filterNameJa2(Theater $theater): string
    {
        $nameJa = $theater->getNameJa();
        $filtered = $nameJa;

        if ($theater->getId() === 20) {
            $br = '<br class="tn_br_gdcs">';

            $filtered
                = mb_substr($nameJa, 0, 4)
                . $br
                . mb_substr($nameJa, 4, 3)
                . $br
                . mb_substr($nameJa, 7);
        }

        return $filtered;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('theater_area', [$this, 'theaterArea']),
            new TwigFunction('theater_meta_keywords', [$this, 'getMetaKeywords']),
        ];
    }

    public function theaterArea(int $area): ?string
    {
        $areas = Theater::getAreas();

        return $areas[$area] ?? null;
    }

    public function getMetaKeywords(Theater $theater): ?string
    {
        return $this->metaKeywords[$theater->getId()] ?? null;
    }
}
