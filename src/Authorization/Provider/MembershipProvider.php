<?php

declare(strict_types=1);

namespace App\Authorization\Provider;

use App\Authorization\Token\AuthorizationCodeToken;
use League\OAuth2\Client\Provider\GenericProvider;

class MembershipProvider extends GenericProvider
{
    private string $urlSignup;
    private string $urlLogout;
    private string $redirectLogoutUri;

    /**
     * @param string[] $scopes
     */
    public function __construct(
        string $host,
        string $clientId,
        string $clientSecret,
        array $scopes,
        string $loginUrl,
        string $logoutUrl
    ) {
        $this->urlSignup         = $host . '/signup';
        $this->urlLogout         = $host . '/logout';
        $this->redirectLogoutUri = $logoutUrl;

        parent::__construct([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $loginUrl,
            'urlAuthorize' => $host . '/authorize',
            'urlAccessToken' => $host . '/token',
            'urlResourceOwnerDetails' => $host . '/unused',
            'scopes' => $scopes,
            'scopeSeparator' => ' ',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getAuthorizationUrl(array $options = [])
    {
        $options['state'] ??= 'login';

        return parent::getAuthorizationUrl($options);
    }

    public function getLogoutUrl(): string
    {
        $options = [
            'state' => 'logout',
            'redirect_uri' => $this->redirectLogoutUri,
        ];

        $params = $this->getAuthorizationParameters($options);

        $params['logout_uri'] = $params['redirect_uri'];
        unset($params['redirect_uri']);

        $query = $this->getAuthorizationQuery($params);

        return $this->appendQuery($this->urlLogout, $query);
    }

    public function getSignupUrl(): string
    {
        $options = ['state' => 'signup'];
        $params  = $this->getAuthorizationParameters($options);
        $query   = $this->getAuthorizationQuery($params);

        return $this->appendQuery($this->urlSignup, $query);
    }

    public function requestAuthorizationCodeToken(string $code): AuthorizationCodeToken
    {
        $accessToken = $this->getAccessToken(
            'authorization_code',
            ['code' => $code]
        );

        return AuthorizationCodeToken::create($accessToken);
    }
}
