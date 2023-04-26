<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization\Token;

use App\Authorization\Token\AuthorizationCodeToken;
use App\Authorization\Token\DecodedAccessToken;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Authorization\Token\AuthorizationCodeToken
 * @testdox Authorization Code Tokenを示すクラス
 */
final class AuthorizationCodeTokenTest extends TestCase
{
    /**
     * @covers ::create
     * @test
     */
    public function Authorization_Code_Tokenオブジェクトを生成する(): void
    {
        // Arrange

        // phpcs:disable Generic.Files.LineLength.TooLong
        $accessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';
        // phpcs:enable

        $data = [
            'access_token' => $accessToken,
            'token_type' => 'example_type',
            'refresh_token' => 'example_refresh_token',
            'expires_in' => 3600,
            'id_token' => 'example_id_token',
        ];

        // Act
        $result = AuthorizationCodeToken::create($data);

        // Assert
        $this->assertEquals($accessToken, $result->getAccessToken());
        $this->assertInstanceOf(DecodedAccessToken::class, $result->getDecodedAccessToken());
        $this->assertEquals('example_type', $result->getTokenType());
        $this->assertEquals('example_refresh_token', $result->getRefreshToken());
        $this->assertEquals(3600, $result->getExpiresIn());
        $this->assertEquals('example_id_token', $result->getIdToken());

        // 暫定的に現在時刻よりも大きいことを確認する
        $this->assertGreaterThan(time(), $result->getExpires());
    }
}
