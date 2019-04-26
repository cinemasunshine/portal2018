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

use Cinemasunshine\Portal\Schedule\Builder\V1\PreSchedule as V1PreScheduleBuilder;
use Cinemasunshine\Portal\Schedule\Builder\V1\Schedule as V1ScheduleBuilder;

use Cinemasunshine\Portal\Schedule\Builder\V2\PreSchedule as V2PreScheduleBuilder;
use Cinemasunshine\Portal\Schedule\Builder\V2\Schedule as V2ScheduleBuilder;

/**
 * Theater class
 */
class Theater extends Base
{
    /** @var string theater name */
    protected $name;
    
    /** @var array */
    protected $config;

    /** @var string[] API endpoint */
    protected $endpoint;

    /** @var Cinemasunshine\Schedule\Client client */
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
     * has test API
     *
     * @param string $theater
     * @return boolean
     */
    public static function hasTestApi(string $theater)
    {
        $config = self::getConfig($theater);
        
        return isset($config['test_endpoint']);
    }

    /**
     * constructor
     *
     * @param string $name
     * @param bool   $useTestApi
     * @throws \InvalidArgumentException
     */
    public function __construct($name, $useTestApi = false)
    {
        if (!self::validate($name)) {
            throw new \InvalidArgumentException(
                sprintf('invalid theater "%s".', $name)
            );
        }

        $this->config = self::getConfig($name);

        if ($useTestApi && !isset($this->config['test_endpoint'])) {
            throw new \InvalidArgumentException(
                sprintf('theater "%s" is cannot use test endpoint.', $name)
            );
        }

        $this->name     = $name;
        $this->endpoint = ($useTestApi)
                        ? $this->config['test_endpoint'] : $this->config['endpoint'];
        $this->client   = new HttpClient();
    }
    
    /**
     * スケジュール取得
     *
     * @param ScheduleBuilder $builder
     * @return mixed
     */
    public function fetchSchedule(ScheduleBuilder $builder = null)
    {
        return $this->client->get($this->endpoint['schedule'], $builder);
    }
    
    /**
     * 先行スケジュール取得
     *
     * @param PreScheduleBuilder $builder
     * @return mixed
     */
    public function fetchPreSchedule(PreScheduleBuilder $builder = null)
    {
        return $this->client->get($this->endpoint['pre_schedule'], $builder);
    }
    
    /**
     * return is version2
     *
     * @return boolean
     */
    public function isVersion2()
    {
        return $this->config['version'] === '2';
    }
}
