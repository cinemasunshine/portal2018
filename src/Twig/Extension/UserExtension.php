<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Authorization\Manager as AuthorizationManager;
use App\User\Manager as UserManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * User twig extension class
 */
class UserExtension extends AbstractExtension
{
    /** @var UserManager */
    protected $userManager;

    /** @var AuthorizationManager */
    protected $authorizationManager;

    public function __construct(UserManager $userManager, AuthorizationManager $authorizationManager)
    {
        $this->userManager          = $userManager;
        $this->authorizationManager = $authorizationManager;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('login_url', [$this, 'getLoginUrl'], [ 'is_safe' => ['all'] ]),
            new TwigFunction('logout_url', [$this, 'getLogoutUrl'], [ 'is_safe' => ['all'] ]),
            new TwigFunction('is_login', [$this, 'isLogin'], [ 'is_safe' => ['all'] ]),
            new TwigFunction('login_user', [$this, 'getUser'], [ 'is_safe' => ['all'] ]),
        ];
    }

    public function getLoginUrl(string $redirectUri): string
    {
        return $this->authorizationManager->getAuthorizationUrl($redirectUri);
    }

    public function getLogoutUrl(string $redirectUri): string
    {
        return $this->authorizationManager->getLogoutUrl($redirectUri);
    }

    /**
     * return authorized user data
     *
     * @return array<string, mixed>|null
     */
    public function getUser(): ?array
    {
        return $this->userManager->getUser();
    }

    public function isLogin(): bool
    {
        return $this->userManager->isAuthenticated();
    }
}
