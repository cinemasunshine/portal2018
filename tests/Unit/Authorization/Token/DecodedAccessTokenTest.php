<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization\Token;

use App\Authorization\Token\DecodedAccessToken;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Authorization\Token\DecodedAccessToken
 * @testdox デコードされた Access Token を示すクラス
 */
final class DecodedAccessTokenTest extends TestCase
{
    /**
     * @covers ::decodeJWT
     * @test
     */
    public function JSON_Web_TokenからDecodedAccessTokenオブジェクトを生成する(): void
    {
        // Arrange
        // phpcs:disable Generic.Files.LineLength.TooLong
        $jwt = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.eW91ci1zZWNyZXQ=';
        // phpcs:enable

        // Act
        $result = DecodedAccessToken::decodeJWT($jwt);

        // Assert
        $this->assertSame(['alg' => 'HS256', 'typ' => 'JWT'], $result->getHeader());
        $this->assertSame(
            ['sub' => '1234567890', 'name' => 'John Doe', 'iat' => 1516239022],
            $result->getClaims()
        );
        $this->assertSame('your-secret', $result->getSignature());
    }

    /**
     * @covers ::decodeJWT
     * @dataProvider invalidJSONWebTokenDataProvider
     * @test
     */
    public function ドットで区切られた３つのセグメントで構成されない場合は例外が発生する(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        DecodedAccessToken::decodeJWT($value);
    }

    /**
     * @return array<string, array{string}>
     */
    public function invalidJSONWebTokenDataProvider(): array
    {
        return [
            'セグメントが２つ' => ['aaa.bbb'],
            'セグメントが４つ' => ['aaa.bbb.ccc.ddd'],
        ];
    }
}
