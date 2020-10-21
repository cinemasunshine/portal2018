<?php

/**
 * DecodedAccessToken.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace App\Authorization\Token;

/**
 * Decoded Access Token class
 *
 * JWTを扱うケースが増えてきたら適宜変更していく。
 */
class DecodedAccessToken
{
    /** @var array */
    protected $header;

    /** @var array */
    protected $claims;

    /** @var string */
    protected $signature;

    /**
     * decode JSON Web Token
     *
     * 最低限のチェックとデコード処理。
     *
     * @param string $token
     * @return self
     */
    public static function decodeJWT(string $token): self
    {
        $segments = explode('.', $token);

        if (count($segments) != 3) {
            throw new \UnexpectedValueException('Invalid number of segments.');
        }

        list($headB64, $claimsB64, $signatureB64) = $segments;

        $header    = json_decode(base64_decode($headB64), true);
        $claims    = json_decode(base64_decode($claimsB64), true);
        $signature = base64_decode($signatureB64);

        return new self($header, $claims, $signature);
    }

    /**
     * construct
     *
     * @param array  $header
     * @param array  $claims
     * @param string $signature
     */
    public function __construct(array $header, array $claims, string $signature)
    {
        $this->header    = $header;
        $this->claims    = $claims;
        $this->signature = $signature;
    }

    /**
     * return header
     *
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * return claims
     *
     * @return array
     */
    public function getClaims(): array
    {
        return $this->claims;
    }

    /**
     * return signature
     *
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }
}
