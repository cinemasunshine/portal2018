<?php

declare(strict_types=1);

namespace Tests\Unit\Twig\Extension;

use App\Twig\Extension\MotionpictureTicketExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * @coversDefaultClass \App\Twig\Extension\MotionpictureTicketExtension
 * @testdox モーションピクチャーのチケットサイトに関するTwig拡張機能
 */
final class MotionpictureTicketExtensionTest extends TestCase
{
    /**
     * @covers ::getFunctions
     * @dataProvider functionNameDataProvider
     * @test
     */
    public function 決まった名称のtwigヘルパー関数が含まれる(string $name): void
    {
        // Arrange
        $extensions = new MotionpictureTicketExtension([]);

        // Act
        $functions = $extensions->getFunctions();

        // Assert
        $functionNames = [];

        foreach ($functions as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
            $functionNames[] = $function->getName();
        }

        $this->assertContains($name, $functionNames);
    }

    /**
     * @return array<array{string}>
     */
    public function functionNameDataProvider(): array
    {
        return [
            ['mp_ticket_inquiry'],
            ['mp_ticket_entrance'],
            ['mp_ticket'],
            ['mp_ticket_transaction'],
        ];
    }

    /**
     * @covers ::getTicketInquiryUrl
     * @test
     */
    public function 劇場ごとの問い合わせURLを返す(): void
    {
        // Arrange
        $theaterCode = '001';
        $settings    = ['ticket_url' => 'https://ticket.example.com'];
        $extension   = new MotionpictureTicketExtension($settings);

        // Act
        $result = $extension->getTicketInquiryUrl($theaterCode);

        // Assert
        $this->assertSame('https://ticket.example.com/inquiry/login?theater=001', $result);
    }

    /**
     * @covers ::getTicketEntranceUrl
     * @test
     */
    public function エントランスURLを返す(): void
    {
        // Arrange
        $settings  = ['ticket_entrance_url' => 'https://entrance.example.com'];
        $extension = new MotionpictureTicketExtension($settings);

        // Act
        $result = $extension->getTicketEntranceUrl();

        // Assert
        $this->assertSame('https://entrance.example.com', $result);
    }

    /**
     * @covers ::getTicketUrl
     * @test
     */
    public function オンラインチケットサイトのURLを返す(): void
    {
        // Arrange
        $settings  = ['ticket_url' => 'https://ticket.example.com'];
        $extension = new MotionpictureTicketExtension($settings);

        // Act
        $result = $extension->getTicketUrl();

        // Assert
        $this->assertSame('https://ticket.example.com', $result);
    }

    /**
     * @covers ::getTransactionUrl
     * @test
     */
    public function 取引URLを返す(): void
    {
        // Arrange
        $settings  = ['ticket_transaction_url' => 'https://transaction.example.com'];
        $extension = new MotionpictureTicketExtension($settings);

        // Act
        $result = $extension->getTransactionUrl();

        // Assert
        $this->assertSame('https://transaction.example.com', $result);
    }
}
