<?php
/**
 * ScheduleController.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller\API;

use Cinemasunshine\Schedule\Entity\V1\Schedules as V1Schedules;
use Cinemasunshine\Schedule\Entity\ScheduleInterface;
use Cinemasunshine\Schedule\Entity\SchedulesInterface;
use Cinemasunshine\Schedule\Exception\RequestException;
use Cinemasunshine\Schedule\Response\Http as HttpResponse;

use Cinemasunshine\Portal\Schedule\Builder\V1\PreSchedule as V1PreScheduleBuilder;
use Cinemasunshine\Portal\Schedule\Builder\V1\Schedule as V1ScheduleBuilder;
use Cinemasunshine\Portal\Schedule\Builder\V2\PreSchedule as V2PreScheduleBuilder;
use Cinemasunshine\Portal\Schedule\Builder\V2\Schedule as V2ScheduleBuilder;
use Cinemasunshine\Portal\Schedule\Collection\Schedule as ScheduleCollection;
use Cinemasunshine\Portal\Schedule\Theater as TheaterSchedule;


/**
 * Schedule controller
 */
class ScheduleController extends BaseController
{
    const API_ENV_PROD = 'prod';
    const API_ENV_PROD_AND_TEST = 'prod_and_test'; // テストが無い劇場もある
    
    /** @var string */
    protected $apiEnv;
    
    /** @var string */
    protected $purchaseBaseUrl;
    
    /**
     * pre execute
     * 
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @return void
     */
    protected function preExecute($request, $response) : void
    {
        $settings = $this->settings['coa_schedule'];
        $this->apiEnv = $settings['env'];
        
        $this->purchaseBaseUrl = $this->settings['mp_ticket']['entrance_url'];
    }
    
    /**
     * テストAPIを使用するか
     *
     * @param string $theater
     * @return boolean
     */
    protected function useTestApi(string $theater)
    {
        $hasTestApi = TheaterSchedule::hasTestApi($theater);
        
        return ($this->apiEnv === self::API_ENV_PROD_AND_TEST && $hasTestApi);
    }
    
    /**
     * index action
     * 
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeIndex($request, $response, $args)
    {
        $theaterName = $args['name'];
        
        $useTestApi = $this->useTestApi($theaterName);
        $theaterSchedule = new TheaterSchedule($theaterName, $useTestApi);
        
        if ($theaterSchedule->isVersion2()) {
            $builer = new V2ScheduleBuilder($this->purchaseBaseUrl);
            $preBuiler = new V2PreScheduleBuilder($this->purchaseBaseUrl);
        } else {
            $builer = new V1ScheduleBuilder();
            $preBuiler = new V1PreScheduleBuilder();
        }
        
        $scheduleResponse = $theaterSchedule->fetchSchedule($builer);
        
        if ($scheduleResponse instanceof HttpResponse) {
            $schedules = $scheduleResponse->getContents();
        } else {
            $schedules = $scheduleResponse;
        }
        
        $preScheduleResponse = $theaterSchedule->fetchPreSchedule($preBuiler);

        if ($preScheduleResponse instanceof HttpResponse) {
            $preSchedules = $preScheduleResponse->getContents();
        } else {
            $preSchedules = $preScheduleResponse;
        }
        
        $meta = array();
        $data = array();

        if (
            $schedules->getError() === V1Schedules::ERROR_OTHER
            || $preSchedules->getError() === V1Schedules::ERROR_OTHER
        ) {
            throw new \RuntimeException('schedule unknown error');

        } else if (
            $schedules->getError() === V1Schedules::ERROR_NO_CONTENT
            && $preSchedules->getError() === V1Schedules::ERROR_NO_CONTENT
        ) {
            $meta['error'] = V1Schedules::ERROR_NO_CONTENT;
            $this->data->set('meta', $meta);
            $this->data->set('data', $data);
            
            return;
        }
        
        if (
            $schedules->getError() === V1Schedules::ERROR_OTHER
            || $preSchedules->getError() === V1Schedules::ERROR_OTHER
        ) {
            throw new \RuntimeException('schedule unknown error');

        } else if (
            $schedules->getError() === V1Schedules::ERROR_NO_CONTENT
            && $preSchedules->getError() === V1Schedules::ERROR_NO_CONTENT
        ) {
            $meta['error'] = V1Schedules::ERROR_NO_CONTENT;
            $this->data->set('meta', $meta);
            $this->data->set('data', $data);
            
            return;
        }

        $meta['error']     = V1Schedules::ERROR_NOT;
        $meta['attention'] = $schedules->getAttention(); // 通常、先行で同じ想定
        
        $allSchedules = $this->mergeSchedule($schedules, $preSchedules);

        foreach ($allSchedules as $schedule) {
            $data[] = $schedule->toArray(false);
        }
        
        $this->data->set('meta', $meta);
        $this->data->set('data', $data);
    }
    
    /**
     * スケジュールをマージ
     *
     * 通常、先行の日付が重複する可能性があることに注意する。
     *
     * @param SchedulesInterface $schedules
     * @param SchedulesInterface $preSchedules
     * @return ScheduleCollection
     */
    protected function mergeSchedule(SchedulesInterface $schedules, SchedulesInterface $preSchedules)
    {
        $allSchedules = new ScheduleCollection();

        $shallowCopy = function(ScheduleInterface $schedule) {
            $class = get_class($schedule);
            $newSchedule = new $class($schedule);
            $newSchedule->setDate($schedule->getDate());
            $newSchedule->setUsable($schedule->getUsable());

            return $newSchedule;
        };

        foreach ($schedules->getSchedule() as $schedule) {
            $allSchedule = $shallowCopy($schedule);
            $allSchedules->add($allSchedule);
        }

        foreach ($preSchedules->getSchedule() as $schedule) {
            // 日付重複判定
            if ($allSchedules->has($schedule->getDate())) {
                if ($schedule->getUsable()) {
                    $allSchedules->get($schedule->getDate())->setUsable(true); // true優先
                }
            } else {
                $allSchedule = $shallowCopy($schedule);
                $allSchedules->add($allSchedule);
            }
        }

        $allSchedules->ksort();

        return $allSchedules;
    }
}