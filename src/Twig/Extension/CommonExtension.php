<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CommonExtension extends AbstractExtension
{
    protected string $appEnv;

    public function __construct(string $appEnv)
    {
        $this->appEnv = $appEnv;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_env', [$this, 'getAppEnv']),
            new TwigFunction('is_app_env', [$this, 'isAppEnv']),
            new TwigFunction('facebook', [$this, 'getFacebookUrl']),
            new TwigFunction('twitter', [$this, 'getTwitterUrl']),
        ];
    }

    public function getAppEnv(): string
    {
        return $this->appEnv;
    }

    /**
     * Is the specific application environment?
     *
     * @param string|string[] $env
     */
    public function isAppEnv($env): bool
    {
        if (is_string($env)) {
            $env = [$env];
        }

        return in_array($this->appEnv, $env);
    }

    public function getFacebookUrl(string $name): string
    {
        return sprintf('https://www.facebook.com/%s', $name);
    }

    public function getTwitterUrl(string $name): string
    {
        return sprintf('https://twitter.com/%s', $name);
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('weekday', [$this, 'weekdayFilter']),
        ];
    }

    public function weekdayFilter(DateTime $datetime): string
    {
        $weekdays = ['日', '月', '火', '水', '木', '金', '土'];

        return $weekdays[(int) $datetime->format('w')];
    }
}
