<?php
/**
 * Auth.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal;

use Zend\Session\Container as SessionContainer;

/**
 * Auth class
 */
class Auth
{
    /** @var array */
    protected $settings;

    /** @var SessionContainer */
    protected $session;

    /** @var string */
    protected $codeChallengeMethod = 'S256';

    /**
     * 認可スコープ一覧
     *
     * @var array
     * @see ::getScopeList()
     */
    protected $authorizeScopeList = [
        'phone',
        'openid',
        'email',
        'aws.cognito.signin.user.admin',
        'profile',
        '<API_URL>/transactions',
        '<API_URL>/events.read-only',
        '<API_URL>/organizations.read-only',
        '<API_URL>/orders.read-only',
        '<API_URL>/places.read-only',
        '<API_URL>/people.contacts',
        '<API_URL>/people.creditCards',
        '<API_URL>/people.ownershipInfos.read-only',
    ];

    /**
     * construct
     *
     * @param array $settings
     */
    public function __construct(array $settings, SessionContainer $session)
    {
        $this->settings = $settings;
        $this->session = $session;
    }

    /**
     * generate hash
     *
     * @param string $name
     * @return string
     */
    protected function generateHash(string $name): string
    {
        $salt = 'csp-salt';
        return md5($salt . uniqid((string) random_int(1, 99999), true) . $name);
    }

    /**
     * Initialize code_verifiery
     *
     * @return void
     */
    public function initCodeVerifier(): void
    {
        $this->session['code_verifiery'] = $this->generateHash('code_verifiery');
    }

    /**
     * return code_verifiery
     *
     * 新たに認証を開始する時は code_verifiery を初期化してください。
     *
     * @return string
     */
    public function getCodeVerifier(): string
    {
        if (!isset($this->session['code_verifiery'])) {
            $this->initCodeVerifier();
        }

        return $this->session['code_verifiery'];
    }

    /**
     * return code_challenge_method
     *
     * @return string
     */
    public function getCodeChallengeMethod(): string
    {
        return $this->codeChallengeMethod;
    }

    /**
     * return code_challenge
     *
     * @return string
     * @throws \LogicException
     */
    public function getCodeChallenge(): string
    {
        $codeVerifiery = $this->getCodeVerifier();
        $codeChallengeMethod = $this->codeChallengeMethod;
        $codeChallenge = '';

        switch ($codeChallengeMethod) {
            case 'S256':
                $codeChallenge = base64_encode(hash('sha256', $codeVerifiery));
                break;

            default:
                throw new \LogicException(sprintf('code_challenge_method does not support %s.', $codeChallengeMethod));
                break;
        }

        return $codeChallenge;
    }

    /**
     * return scope list
     *
     * @return array
     */
    public function getScopeList(): array
    {
        // build list
        $apiUrl = 'https://' . $this->settings['api_host'];
        $scopeList = str_replace('<API_URL>', $apiUrl, $this->authorizeScopeList);

        return $scopeList;
    }
}
