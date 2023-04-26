<?php

declare(strict_types=1);

namespace App\Authorization\Token;

class AuthorizationCodeToken extends AbstractToken
{
    protected string $accessToken;
    protected DecodedAccessToken $decodedAccessToken;
    protected string $tokenType;
    protected string $refreshToken;
    protected int $expiresIn;
    protected int $expires;
    protected string $idToken;

    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): self
    {
        $token = new self();

        $token->accessToken        = $data['access_token'];
        $token->decodedAccessToken = DecodedAccessToken::decodeJWT($token->accessToken);
        $token->tokenType          = $data['token_type'];
        $token->refreshToken       = $data['refresh_token'];
        $token->expiresIn          = (int) $data['expires_in'];
        $token->expires            = time() + $token->expiresIn;
        $token->idToken            = $data['id_token'];

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

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
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
