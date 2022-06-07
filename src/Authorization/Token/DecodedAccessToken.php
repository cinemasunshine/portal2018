<?php

declare(strict_types=1);

namespace App\Authorization\Token;

use UnexpectedValueException;

/**
 * Decoded Access Token class
 *
 * JWTを扱うケースが増えてきたら適宜変更していく。
 */
class DecodedAccessToken
{
    /** @var array<string, string> */
    protected array $header;

    /** @var array<string, mixed> */
    protected array $claims;

    protected string $signature;

    /**
     * decode JSON Web Token
     *
     * 最低限のチェックとデコード処理。
     */
    public static function decodeJWT(string $token): self
    {
        $segments = explode('.', $token);

        if (count($segments) !== 3) {
            throw new UnexpectedValueException('Invalid number of segments.');
        }

        [$headB64, $claimsB64, $signatureB64] = $segments;

        $header    = json_decode(base64_decode($headB64), true);
        $claims    = json_decode(base64_decode($claimsB64), true);
        $signature = base64_decode($signatureB64);

        return new self($header, $claims, $signature);
    }

    /**
     * @param array<string, string> $header
     * @param array<string, mixed>  $claims
     */
    public function __construct(array $header, array $claims, string $signature)
    {
        $this->header    = $header;
        $this->claims    = $claims;
        $this->signature = $signature;
    }

    /**
     * @return array<string, string>
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * @return array<string, mixed>
     */
    public function getClaims(): array
    {
        return $this->claims;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }
}
