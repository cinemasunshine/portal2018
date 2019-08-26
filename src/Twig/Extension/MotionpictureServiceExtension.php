<?php
/**
 * MotionpictureServiceExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\Authorization\Manager as AuthorizationManager;

/**
 * Motionpicture Service twig extension class
 */
class MotionpictureServiceExtension extends \Twig_Extension
{
    /** @var AuthorizationManager */
    protected $authorizationManager;

    /** @var array */
    protected $settings;

    /**
     * construct
     *
     * @param array $settings
     * @param Auth $auth
     */
    public function __construct(array $settings, AuthorizationManager $authorizationManager)
    {
        $this->settings = $settings;
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
            new \Twig_Function('mp_login_url', [$this, 'getLoginUrl'], [ 'is_safe' => ['html'] ]),
            new \Twig_Function('mp_ticket_inquiry', [$this, 'getTicketInquiryUrl'], [ 'is_safe' => ['html'] ]),
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
     * craete scope string
     *
     * @param array $scopeList
     * @return string
     */
    protected function createScopeStr(array $scopeList): string
    {
        return implode(' ', $scopeList);
    }

    /**
     * return ticket inquiry URL
     *
     * @param string $theaterCode
     * @return string
     */
    public function getTicketInquiryUrl(string $theaterCode): string
    {
        $path = sprintf('/inquiry/login?theater=%s', $theaterCode);

        return $this->settings['ticket_url'] . $path;
    }
}
