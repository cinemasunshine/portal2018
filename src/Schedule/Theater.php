<?php

declare(strict_types=1);

namespace App\Schedule;

use Cinemasunshine\Schedule\Builder\ScheduleInterface as ScheduleBuilder;
use Cinemasunshine\Schedule\Client\Http as HttpClient;
use Cinemasunshine\Schedule\Config;
use Cinemasunshine\Schedule\Service;
use Cinemasunshine\Schedule\Theater as Base;
use InvalidArgumentException;

/**
 * Theater class
 */
class Theater extends Base
{
    /** @var string theater name */
    protected $name;

    /** @var string */
    protected $version;

    /** @var string */
    protected $xml;

    /** @var HttpClient client */
    protected $client;

    /**
     * @return array<string, mixed>
     */
    protected static function getConfig(string $theater): array
    {
        $configName = self::CONFIG_PREFIX . $theater;

        return Config::get($configName);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $name, string $environment)
    {
        if (! self::validate($name)) {
            throw new InvalidArgumentException(
                sprintf('invalid theater "%s".', $name)
            );
        }

        $this->name = $name;

        $config = self::getConfig($name);

        $this->version = $config['version'];
        $this->xml     = $config['xml'];

        $baseUrl = Service::getBaseUrl($environment);

        $this->client = new HttpClient([
            'base_uri' => $baseUrl,
            'timeout'  => 10,
        ]);
    }

    /**
     * スケジュール取得
     *
     * @return mixed
     */
    public function fetchSchedule(?ScheduleBuilder $builder = null)
    {
        return $this->client->get($this->xml, $builder);
    }

    public function isVersion(string $version): bool
    {
        return $this->version === $version;
    }
}
