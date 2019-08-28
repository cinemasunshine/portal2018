<?php
/**
 * AccessToken.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Authorization\Token;

/**
 * Access Token class
 */
class AccessToken
{
    /** @var string */
    protected $token;

    /** @var string|null */
    protected $tokenType;

    /** @var string|null */
    protected $refreshToken;

    /** @var int|null */
    protected $expiresIn;

    /** @var string|null */
    protected $idToken;

    /**
     * construct
     *
     * @param array $response
     * @throws \InvalidArgumentException
     */
    public function __construct(array $response)
    {
        if (empty($response['access_token'])) {
            throw new \InvalidArgumentException('Required "access_token".');
        }

        $this->token = $response['access_token'];

        if (!empty($response['token_type'])) {
            $this->tokenType = $response['token_type'];
        }

        if (!empty($response['refresh_token'])) {
            $this->refreshToken = $response['refresh_token'];
        }

        if (!empty($response['expires_in'])) {
            $this->expiresIn = $response['expires_in'];
        }

        if (!empty($response['id_token'])) {
            $this->idToken = $response['id_token'];
        }
    }

    /**
     * return access_token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * decode access_token
     *
     * @return DecodedAccessToken
     */
    public function decodeToken(): DecodedAccessToken
    {
        return DecodedAccessToken::decodeJWT($this->token);
    }

    /**
     * return token_type
     *
     * @return string|null
     */
    public function getTokenType(): ?string
    {
        return $this->getTokenType();
    }

    /**
     * return refresh_token
     *
     * @return string|null
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * return expires_in
     *
     * @return integer|null
     */
    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }

    /**
     * return id_token
     *
     * @return string|null
     */
    public function getIdToken(): ?string
    {
        return $this->idToken;
    }
}
