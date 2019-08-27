<?php
/**
 * AuthorizationExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\Authorization\Manager as AuthorizationManager;

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
            new \Twig_Function('login_url', [$this, 'getLoginUrl'], [ 'is_safe' => ['html'] ]),
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
}
