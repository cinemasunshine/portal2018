<?php
/**
 * AuthorizationCodeToken.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Authorization\Token;

/**
 * Authorization Code Token class
 */
class AuthorizationCodeToken extends AbstractToken
{
    /** @var string */
    protected $accessToken;

    /** @var string */
    protected $tokenType;

    /** @var string */
    protected $refreshToken;

    /** @var int */
    protected $expiresIn;

    /** @var string */
    protected $idToken;

    /**
     * create token
     *
     * @param string $json
     * @return self
     */
    public static function create(string $json): self
    {
        $data = json_decode($json, true);

        $token = new self();
        $token->setAccessToken($data['access_token']);
        $token->setTokenType($data['token_type']);
        $token->setRefreshToken($data['refresh_token']);
        $token->setExpiresIn((int) $data['expires_in']);
        $token->setIdToken($data['id_token']);

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
     * decode access_token
     *
     * @return DecodedAccessToken
     */
    public function decodeAccessToken(): DecodedAccessToken
    {
        return DecodedAccessToken::decodeJWT($this->accessToken);
    }

    /**
     * return token_type
     *
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->getTokenType();
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
     * return refresh_token
     *
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * set refresh_token
     *
     * @param string $refreshToken
     * @return void
     */
    protected function setRefreshToken(string $refreshToken)
    {
        $this->refreshToken = $refreshToken;
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
    protected function setExpiresIn(int $expiresIn)
    {
        $this->expiresIn = $expiresIn;
    }

    /**
     * return id_token
     *
     * @return string
     */
    public function getIdToken(): string
    {
        return $this->idToken;
    }

    /**
     * set id_token
     *
     * @param string $idToken
     * @return void
     */
    protected function setIdToken(string $idToken)
    {
        $this->idToken = $idToken;
    }
}
