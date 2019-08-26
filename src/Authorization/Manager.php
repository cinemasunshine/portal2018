<?php
/**
 * Manager.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Authorization;

use Zend\Session\Container as SessionContainer;

use Cinemasunshine\Portal\Authorization\Grant\AuthorizationCode as AuthorizationCodeGrant;

/**
 * Authorization Manager class
 */
class Manager
{
    /** @var SessionContainer */
    protected $session;

    /** @var string */
    protected $codeChallengeMethod = 'S256';

    /** @var array */
    protected $authorizeScopeList;

    /** @var AuthorizationCodeGrant */
    protected $authorizationCodeGrunt;

    /**
     * construct
     *
     * @param array $settings
     */
    public function __construct(array $settings, SessionContainer $session)
    {
        $this->authorizeScopeList = $this->buildAuthorizeScopeList(
            $settings['authorization_code_scope'],
            $settings['api_host']
        );

        $this->authorizationCodeGrunt = new AuthorizationCodeGrant(
            $settings['authorization_code_host'],
            $settings['authorization_code_client_id'],
            $settings['authorization_code_client_secret']
        );

        $this->session = $session;
    }

    /**
     * return Authorization URL
     *
     * @param string $redirectUri
     * @return string
     */
    public function getAuthorizationUrl(string $redirectUri): string
    {
        return $this->authorizationCodeGrunt->getAuthorizationUrl(
            $this->getCodeVerifier(),
            $redirectUri,
            $this->authorizeScopeList,
            'todo'
        );
    }

    /**
     * Initialize code_verifier
     *
     * @return void
     */
    protected function initCodeVerifier(): void
    {
        $this->session['code_verifier'] = $this->authorizationCodeGrunt->createCodeVerifier();
    }

    /**
     * return code_verifier
     *
     * 新たに認証を開始する時は code_verifier を初期化してください。
     *
     * @return string
     */
    protected function getCodeVerifier(): string
    {
        if (!isset($this->session['code_verifier'])) {
            $this->initCodeVerifier();
        }

        return $this->session['code_verifier'];
    }

    /**
     * build authorize scope list
     *
     * @return array
     */
    protected function buildAuthorizeScopeList(array $baseScopeList, string $apiHost): array
    {
        $apiUrl = 'https://' . $apiHost;
        $scopeList = str_replace('<API_URL>', $apiUrl, $baseScopeList);

        return $scopeList;
    }
}
