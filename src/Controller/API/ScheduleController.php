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
        $response = $theaterSchedule->fetchSchedule();
        $preResponse = $theaterSchedule->fetchPreSchedule();
        
        if ($response instanceof HttpResponse) {
            $schedules = $response->getContents();
        } else {
            $schedules = $response;
        }

        if ($response instanceof HttpResponse) {
            $preSchedules = $preResponse->getContents();
        } else {
            $preSchedules = $preResponse;
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