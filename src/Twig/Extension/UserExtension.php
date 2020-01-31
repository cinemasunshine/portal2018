<?php
/**
 * UserExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use Cinemasunshine\Portal\Authorization\Manager as AuthorizationManager;
use Cinemasunshine\Portal\User\Manager as UserManager;

/**
 * User twig extension class
 */
class UserExtension extends AbstractExtension
{
    /** @var UserManager */
    protected $userManager;

    /** @var AuthorizationManager */
    protected $authorizationManager;

    /**
     * construct
     *
     * @param AuthorizationManager $authorizationManager
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager, AuthorizationManager $authorizationManager)
    {
        $this->userManager = $userManager;
        $this->authorizationManager = $authorizationManager;
    }

    /**
     * get functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('login_url', [$this, 'getLoginUrl'], [ 'is_safe' => ['all'] ]),
            new TwigFunction('logout_url', [$this, 'getLogoutUrl'], [ 'is_safe' => ['all'] ]),
            new TwigFunction('is_login', [$this, 'isLogin'], [ 'is_safe' => ['all'] ]),
            new TwigFunction('login_user', [$this, 'getUser'], [ 'is_safe' => ['all'] ]),
        ];
    }

    /**
     * return Login URL
     *
     * @param string $redirectUri
     * @return string
     */
    public function getLoginUrl(string $redirectUri): string
    {
        return $this->authorizationManager->getAuthorizationUrl($redirectUri);
    }

    /**
     * return logout URL
     *
     * @param string $redirectUri
     * @return string
     */
    public function getLogoutUrl(string $redirectUri): string
    {
        return $this->authorizationManager->getLogoutUrl($redirectUri);
    }

    /**
     * return authorized user data
     *
     * @return array|null
     */
    public function getUser(): ?array
    {
        return $this->userManager->getUser();
    }

    /**
     * ログイン判定
     *
     * @return boolean
     */
    public function isLogin(): bool
    {
        return $this->userManager->isAuthenticated();
    }
}
