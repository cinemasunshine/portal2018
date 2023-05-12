<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Authorization\RewardManager as RewardAuthorizationManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RewardExtension extends AbstractExtension
{
    protected RewardAuthorizationManager $authorizationManager;

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('reward_login_url', [$this, 'getLoginUrl'], [ 'is_safe' => ['all'] ]),
            new TwigFunction('reward_logout_url', [$this, 'getLogoutUrl'], [ 'is_safe' => ['all'] ]),
        ];
    }

    public function __construct(RewardAuthorizationManager $authorizationManager)
    {
        $this->authorizationManager = $authorizationManager;
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
