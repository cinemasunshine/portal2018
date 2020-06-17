<?php

/**
 * ClientCredentialsToken.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Authorization\Token;

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
     * create token
     *
     * @param array $data
     * @return self
     */
    public static function create(array $data): self
    {
        $token = new self();
        $token->setAccessToken($data['access_token']);
        $token->setTokenType($data['token_type']);
        $token->setExpiresIn((int) $data['expires_in']);

        return $token;
    }

    /**
     * construct
     */
    protected function __construct()
    {
    }

    /**
     * return access_token
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * set access_token
     *
     * @param string $accessToken
     * @return void
     */
    protected function setAccessToken(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * return token_type
     *
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * set token_type
     *
     * @param string $tokenType
     * @return void
     */
    protected function setTokenType(string $tokenType)
    {
        $this->tokenType = $tokenType;
    }

    /**
     * return expires_in
     *
     * @return integer
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * set expires_in
     *
     * @param integer $expiresIn
     * @return void
     */
    public function setExpiresIn(int $expiresIn)
    {
        $this->expiresIn = $expiresIn;
    }
}
