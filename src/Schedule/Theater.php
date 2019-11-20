<?php
/**
 * Theater.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Schedule;

use Cinemasunshine\Schedule\Theater as Base;

use Cinemasunshine\Schedule\Builder\PreScheduleInterface as PreScheduleBuilder;
use Cinemasunshine\Schedule\Builder\ScheduleInterface as ScheduleBuilder;
use Cinemasunshine\Schedule\Client\Http as HttpClient;
use Cinemasunshine\Schedule\Config;
use Cinemasunshine\Schedule\Service;

/**
 * Theater class
 */
class Theater extends Base
{
    /** @var string theater name */
    protected $name;

    /** @var string */
    protected $version;

    /** @var string[] */
    protected $xml;

    /** @var HttpClient client */
    protected $client;

    /**
     * return config
     *
     * @param string $theater
     * @return array
     */
    protected static function getConfig(string $theater)
    {
        $configName = self::CONFIG_PREFIX . $theater;

        return Config::get($configName);
    }

    /**
     * constructor
     *
     * @param string $name
     * @param string $environment
     * @throws \InvalidArgumentException
     */
    public function __construct(string $name, string $environment)
    {
        if (!self::validate($name)) {
            throw new \InvalidArgumentException(
                sprintf('invalid theater "%s".', $name)
            );
        }

        $this->name = $name;

        $config = self::getConfig($name);
        $this->version = $config['version'];
        $this->xml = $config['xml'];

        $baseUrl = Service::getBaseUrl($environment);
        $this->client   = new HttpClient([
            'base_uri' => $baseUrl,
        ]);
    }

    /**
     * スケジュール取得
     *
     * @param ScheduleBuilder $builder
     * @return mixed
     */
    public function fetchSchedule(ScheduleBuilder $builder = null)
    {
        return $this->client->get($this->xml['schedule'], $builder);
    }

    /**
     * 先行スケジュール取得
     *
     * @param PreScheduleBuilder $builder
     * @return mixed
     */
    public function fetchPreSchedule(PreScheduleBuilder $builder = null)
    {
        return $this->client->get($this->xml['pre_schedule'], $builder);
    }

    /**
     * is version
     *
     * @param string $version
     * @return boolean
     */
    public function isVersion(string $version): bool
    {
        return $this->version === $version;
    }
}
