<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Schedule twig extension class
 */
class ScheduleExtension extends AbstractExtension
{
    /** @var array<string, mixed> */
    protected array $settings;

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
            new TwigFunction('schedule_api', [$this, 'getApiUrl'], [ 'is_safe' => ['html'] ]),
        ];
    }

    public function getApiUrl(): string
    {
        return $this->settings['api_url'];
    }
}
