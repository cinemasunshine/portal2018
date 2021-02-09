<?php

declare(strict_types=1);

namespace App\Authorization\Token;

/**
 * Client Credentials Token class
 */
class ClientCredentialsToken extends AbstractToken
{
    /** @var string */
    protected $accessToken;

    /** @var string */
    protected $tokenType;

    /** @var int */
    protected $expiresIn;

    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): self
    {
        $token = new self();
        $token->setAccessToken($data['access_token']);
        $token->setTokenType($data['token_type']);
        $token->setExpiresIn((int) $data['expires_in']);

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

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    protected function setTokenType(string $tokenType): void
    {
        $this->tokenType = $tokenType;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(int $expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }
}
