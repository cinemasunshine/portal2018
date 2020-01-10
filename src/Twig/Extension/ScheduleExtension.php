<?php
/**
 * ScheduleExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace Cinemasunshine\Portal\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Schedule twig extension class
 */
class ScheduleExtension extends AbstractExtension
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
            new TwigFunction('schedule_api', [$this, 'getApiUrl'], [ 'is_safe' => ['html'] ]),
        ];
    }

    /**
     * return API URL
     *
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->settings['api_url'];
    }
}
