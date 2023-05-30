<?php

declare(strict_types=1);

namespace Tests\Unit\User\Provider;

use League\OAuth2\Client\Token\AccessTokenInterface;

class TestAccessToken implements AccessTokenInterface
{
    private string $accessToken;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @inheritdoc
     */
    public function getToken()
    {
        return $this->accessToken;
    }

    /**
     * @inheritdoc
     */
    public function getRefreshToken()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function getExpires()
    {
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function hasExpired()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        return [
            'token_type' => '',
            'id_token' => '',
        ];
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return (string) $this->getToken();
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [];
    }
}
