<?php
/**
 * MotionpictureServiceExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\Auth;

/**
 * Motionpicture Service twig extension class
 */
class MotionpictureServiceExtension extends \Twig_Extension
{
    /** @var Auth */
    protected $auth;

    /** @var array */
    protected $settings;

    /**
     * construct
     *
     * @param array $settings
     * @param Auth $auth
     */
    public function __construct(array $settings, Auth $auth)
    {
        $this->settings = $settings;
        $this->auth = $auth;
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
     * @link https://m-p.backlog.jp/view/SASAKI-485
     */
    public function getLoginUrl(string $redirectUri): string
    {
        $auth = $this->auth;
        $auth->initCodeVerifier();

        $scope = $this->createScopeStr($auth->getScopeList());
        $params = [
            'response_type'         => 'code',
            'client_id'             => $this->settings['auth_client_id'],
            'redirect_uri'          => $redirectUri,
            'scope'                 => $scope,
            'state'                 => 'todo', // TODO
            'code_challenge_method' => $auth->getCodeChallengeMethod(),
            'code_challenge'        => $auth->getCodeChallenge(),
        ];

        $base = 'https://' . $this->settings['auth_host'] . '/authorize';

        return $base . '?' . http_build_query($params);
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
