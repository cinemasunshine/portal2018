<?php
/**
 * AuthorizationExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\Authorization\Manager as AuthorizationManager;
use Cinemasunshine\Portal\Authorization\Token\AccessToken;

/**
 * Authorization twig extension class
 */
class AuthorizationExtension extends \Twig_Extension
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
            new \Twig_Function('login_url', [$this, 'getLoginUrl'], [ 'is_safe' => ['all'] ]),
            new \Twig_Function('is_login', [$this, 'isLogin'], [ 'is_safe' => ['all'] ]),
            new \Twig_Function('access_token', [$this, 'getAccessToken'], [ 'is_safe' => ['all'] ]),
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
        return $this->authorizationManager->isLogin();
    }

    /**
     * return access token
     *
     * @return AccessToken|null
     */
    public function getAccessToken(): ?AccessToken
    {
        return $this->authorizationManager->getAccessToken();
    }
}
