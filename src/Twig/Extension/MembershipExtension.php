<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MembershipExtension extends AbstractExtension
{
    private string $membershipSiteUrl;

    public function __construct(string $membershipSiteUrl)
    {
        $this->membershipSiteUrl = $membershipSiteUrl;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('membership_mypage_url', [$this, 'getMypageUrl'], [ 'is_safe' => ['html'] ]),
            new TwigFunction('membership_signup_url', [$this, 'getSignupUrl'], [ 'is_safe' => ['html'] ]),
            new TwigFunction('membership_login_url', [$this, 'getLoginUrl'], [ 'is_safe' => ['html'] ]),
            new TwigFunction('membership_logout_url', [$this, 'getLogoutUrl'], [ 'is_safe' => ['html'] ]),
        ];
    }

    public function getMypageUrl(): string
    {
        return $this->membershipSiteUrl;
    }

    public function getSignupUrl(): string
    {
        return $this->membershipSiteUrl;
    }

    public function getLoginUrl(): string
    {
        return $this->membershipSiteUrl;
    }

    public function getLogoutUrl(): string
    {
        return $this->membershipSiteUrl . '/logout';
    }
}
