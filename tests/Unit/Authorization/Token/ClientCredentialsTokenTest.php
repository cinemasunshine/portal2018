<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization\Token;

use App\Authorization\Token\ClientCredentialsToken;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Authorization\Token\ClientCredentialsToken
 * @testdox Client Credentials Tokenを示すクラス
 */
final class ClientCredentialsTokenTest extends TestCase
{
    /**
     * @covers ::create
     * @test
     */
    public function Client_Credentials_Tokenオブジェクトを生成する(): void
    {
        // Arrange
        $data = [
            'access_token' => 'example_access_token',
            'token_type' => 'example_type',
            'expires_in' => 3600,
        ];

        // Act
        $result = ClientCredentialsToken::create($data);

        // Assert
        $this->assertSame('example_access_token', $result->getAccessToken());
        $this->assertSame('example_type', $result->getTokenType());
        $this->assertSame(3600, $result->getExpiresIn());
    }
}
