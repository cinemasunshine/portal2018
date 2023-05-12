<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Authorization\MembershipManager as AuthorizationManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MembershipExtension extends AbstractExtension
{
    private string $mypageUrl;
    private AuthorizationManager $authorizationManager;

    public function __construct(string $mypageUrl, AuthorizationManager $authorizationManager)
    {
        $this->mypageUrl            = $mypageUrl;
        $this->authorizationManager = $authorizationManager;
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
        return $this->mypageUrl;
    }

    public function getSignupUrl(): string
    {
        return $this->authorizationManager->getSignupUrl();
    }

    public function getLoginUrl(): string
    {
        return $this->authorizationManager->getAuthorizationUrl();
    }

    public function getLogoutUrl(): string
    {
        return $this->authorizationManager->getLogoutUrl();
    }
}
