<?php

declare(strict_types=1);

namespace App\Authorization\Token;

use League\OAuth2\Client\Token\AccessTokenInterface;

class AuthorizationCodeToken
{
    protected string $accessToken;
    protected DecodedAccessToken $decodedAccessToken;
    protected string $tokenType;
    protected string $refreshToken;
    protected int $expires;
    protected string $idToken;

    public static function create(AccessTokenInterface $accessToken): self
    {
        $token = new self();

        $token->accessToken        = $accessToken->getToken();
        $token->decodedAccessToken = DecodedAccessToken::decodeJWT($token->accessToken);
        $token->tokenType          = $accessToken->getValues()['token_type'];
        $token->refreshToken       = $accessToken->getRefreshToken();
        $token->expires            = $accessToken->getExpires();
        $token->idToken            = $accessToken->getValues()['id_token'];

        return $token;
    }

    protected function __construct()
    {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getDecodedAccessToken(): DecodedAccessToken
    {
        return $this->decodedAccessToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getExpires(): int
    {
        return $this->expires;
    }

    public function getIdToken(): string
    {
        return $this->idToken;
    }
}
