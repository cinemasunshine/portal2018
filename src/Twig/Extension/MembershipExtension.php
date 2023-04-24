<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MembershipExtension extends AbstractExtension
{
    /** @var array<string, mixed> */
    protected array $settings;

    /**
     * @param array<string, mixed> $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('membership_mypage_url', [$this, 'getMypageUrl'], [ 'is_safe' => ['html'] ]),
        ];
    }

    public function getMypageUrl(): string
    {
        return $this->settings['mypage_url'];
    }
}
