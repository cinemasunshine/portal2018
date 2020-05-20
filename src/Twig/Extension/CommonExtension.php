<?php

/**
 * CommonExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Cinemasunshine\Portal\ORM\Entity\Theater;

/**
 * Common twig extension class
 */
class CommonExtension extends AbstractExtension
{
    /** @var string */
    protected $appEnv;

    /**
     * construct
     */
    public function __construct()
    {
        $this->appEnv = APP_ENV;
    }

    /**
     * get functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('app_env', [$this, 'getAppEnv']),
            new TwigFunction('is_app_env', [$this, 'isAppEnv']),
            new TwigFunction('facebook', [$this, 'getFacebookUrl']),
            new TwigFunction('twitter', [$this, 'getTwitterUrl']),
        ];
    }

    /**
     * return application environment
     *
     * @return string
     */
    public function getAppEnv(): string
    {
        return $this->appEnv;
    }

    /**
     * Is the specific application environment?
     *
     * @param string|array $env
     * @return boolean
     */
    public function isAppEnv($env): bool
    {
        if (is_string($env)) {
            $env = [ $env ];
        }

        return in_array($this->appEnv, $env);
    }

    /**
     * retrun facebook URL
     *
     * @param string $name
     * @return string
     */
    public function getFacebookUrl(string $name)
    {
        return sprintf('https://www.facebook.com/%s', $name);
    }

    /**
     * return twitter URL
     *
     * @param string $name
     * @return string
     */
    public function getTwitterUrl(string $name)
    {
        return sprintf('https://twitter.com/%s', $name);
    }

    /**
     * get filters
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new TwigFilter('weekday', [$this, 'weekdayFilter']),
        );
    }

    /**
     * return weekday
     *
     * @param \DateTime $datetime
     * @return string
     */
    public function weekdayFilter(\DateTime $datetime)
    {
        $weekdays = ['日', '月', '火', '水', '木', '金', '土'];

        return $weekdays[(int) $datetime->format('w')];
    }
}
