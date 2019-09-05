<?php
/**
 * AuthorizationExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use Cinemasunshine\Portal\Authorization\Manager as AuthorizationManager;

/**
 * Authorization twig extension class
 */
class AuthorizationExtension extends AbstractExtension
{
    /** @var AuthorizationManager */
    protected $authorizationManager;

    /**
     * construct
     *
     * @param AuthorizationManager $authorizationManager
     */
    public function __construct(AuthorizationManager $authorizationManager)
    {
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
            new TwigFunction('is_login', [$this, 'isLogin'], [ 'is_safe' => ['all'] ]),
            new TwigFunction('login_user', [$this, 'getUser'], [ 'is_safe' => ['all'] ]),
            new TwigFunction('logout_url', [$this, 'getLogoutUrl'], [ 'is_safe' => ['all'] ]),
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
     * ログイン判定
     *
     * @return boolean
     */
    public function isLogin(): bool
    {
        return $this->authorizationManager->isAuthorized();
    }

    /**
     * return authorized user
     *
     * @return array|null
     */
    public function getUser(): ?array
    {
        return $this->authorizationManager->getUser();
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
}
