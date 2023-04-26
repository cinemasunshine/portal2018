<?php

declare(strict_types=1);

namespace App\Authorization\Token;

class ClientCredentialsToken extends AbstractToken
{
    protected string $accessToken;
    protected string $tokenType;
    protected int $expiresIn;

    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): self
    {
        $token = new self();

        $token->accessToken = $data['access_token'];
        $token->tokenType   = $data['token_type'];
        $token->expiresIn   = (int) $data['expires_in'];

        return $token;
    }

    protected function __construct()
    {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }
}
