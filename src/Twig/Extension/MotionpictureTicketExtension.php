<?php

/**
 * MotionpictureTicketExtension.php
 */

declare(strict_types=1);

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Motionpicture Ticket twig extension class
 */
class MotionpictureTicketExtension extends AbstractExtension
{
    /** @var array */
    protected $settings;

    /**
     * construct
     *
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * get functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('mp_ticket_inquiry', [$this, 'getTicketInquiryUrl'], [ 'is_safe' => ['html'] ]),
            new TwigFunction('mp_ticket_entrance', [$this, 'getTicketEntranceUrl'], [ 'is_safe' => ['html'] ]),
        ];
    }

    /**
     * return ticket inquiry URL
     *
     * @param string $theaterCode
     * @return string
     */
    public function getTicketInquiryUrl(string $theaterCode): string
    {
        $path = sprintf('/inquiry/login?theater=%s', $theaterCode);

        return $this->settings['ticket_url'] . $path;
    }

    /**
     * return ticket entrance URL
     *
     * @return string
     */
    public function getTicketEntranceUrl(): string
    {
        return $this->settings['ticket_entrance_url'];
    }
}
