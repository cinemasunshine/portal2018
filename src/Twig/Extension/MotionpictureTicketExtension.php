<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Motionpicture Ticket twig extension class
 */
class MotionpictureTicketExtension extends AbstractExtension
{
    /** @var array<string, mixed> */
    protected $settings;

    /**
     * @param array<string, mixed> $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('mp_ticket_inquiry', [$this, 'getTicketInquiryUrl'], [ 'is_safe' => ['html'] ]),
            new TwigFunction('mp_ticket_entrance', [$this, 'getTicketEntranceUrl'], [ 'is_safe' => ['html'] ]),
        ];
    }

    public function getTicketInquiryUrl(string $theaterCode): string
    {
        $path = sprintf('/inquiry/login?theater=%s', $theaterCode);

        return $this->settings['ticket_url'] . $path;
    }

    public function getTicketEntranceUrl(): string
    {
        return $this->settings['ticket_entrance_url'];
    }
}
