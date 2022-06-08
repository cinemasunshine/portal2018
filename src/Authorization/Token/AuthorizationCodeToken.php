<?php

declare(strict_types=1);

namespace App\Authorization\Token;

/**
 * Authorization Code Token class
 */
class AuthorizationCodeToken extends AbstractToken
{
    protected string $accessToken;

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
        $token->setAccessToken($data['access_token']);
        $token->setTokenType($data['token_type']);
        $token->setRefreshToken($data['refresh_token']);

        $expiresIn = (int) $data['expires_in'];
        $token->setExpiresIn($expiresIn);

        $expires = time() + $expiresIn;
        $token->setExpires($expires);

        $token->setIdToken($data['id_token']);

        return $token;
    }

    protected function __construct()
    {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    protected function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function decodeAccessToken(): DecodedAccessToken
    {
        return DecodedAccessToken::decodeJWT($this->accessToken);
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    protected function setTokenType(string $tokenType): void
    {
        $this->tokenType = $tokenType;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    protected function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    protected function setExpiresIn(int $expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }

    public function getExpires(): int
    {
        return $this->expires;
    }

    protected function setExpires(int $expires): void
    {
        $this->expires = $expires;
    }

    public function getIdToken(): string
    {
        return $this->idToken;
    }

    protected function setIdToken(string $idToken): void
    {
        $this->idToken = $idToken;
    }
}
