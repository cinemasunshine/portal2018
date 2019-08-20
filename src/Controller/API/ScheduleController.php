<?php
/**
 * ScheduleController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller\API;

use Slim\Exception\NotFoundException;

use Cinemasunshine\Schedule\Entity\V2\Schedules as V2Schedules;
use Cinemasunshine\Schedule\Entity\ScheduleInterface;
use Cinemasunshine\Schedule\Entity\SchedulesInterface;
use Cinemasunshine\Schedule\Exception\RequestException;
use Cinemasunshine\Schedule\Response\Http as HttpResponse;

use Cinemasunshine\Portal\Schedule\Builder\V2\PreSchedule as V2PreScheduleBuilder;
use Cinemasunshine\Portal\Schedule\Builder\V2\Schedule as V2ScheduleBuilder;
use Cinemasunshine\Portal\Schedule\Collection\Movie as MovieCollection;
use Cinemasunshine\Portal\Schedule\Collection\Schedule as ScheduleCollection;
use Cinemasunshine\Portal\Schedule\Theater as TheaterSchedule;

/**
 * Schedule controller
 */
class ScheduleController extends BaseController
{
    const API_ENV_PROD = 'prod';
    const API_ENV_TEST = 'test';
    
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
     * @return boolean
     */
    protected function useTestApi()
    {
        return $this->apiEnv === self::API_ENV_TEST;
    }
    
    /**
     * index action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     * @throws NotFoundException
     * @todo エラー系のレスポンス検討
     */
    public function executeIndex($request, $response, $args)
    {
        $theaterName = $args['name'];
        
        if (!TheaterSchedule::validate($theaterName)) {
            // ひとまずNotFoundとする SASAKI-338
            throw new NotFoundException($request, $response);
        }
        
        $useTestApi = $this->useTestApi();
        $theaterSchedule = new TheaterSchedule($theaterName, $useTestApi);
        
        $builer = new V2ScheduleBuilder($this->purchaseBaseUrl);
        $preBuiler = new V2PreScheduleBuilder($this->purchaseBaseUrl);
        
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

        if ($schedules->getError() === V2Schedules::ERROR_OTHER
            || $preSchedules->getError() === V2Schedules::ERROR_OTHER
        ) {
            throw new \RuntimeException('schedule unknown error');
        } elseif ($schedules->getError() === V2Schedules::ERROR_NO_CONTENT
            && $preSchedules->getError() === V2Schedules::ERROR_NO_CONTENT
        ) {
            $meta['error'] = V2Schedules::ERROR_NO_CONTENT;
            $this->data->set('meta', $meta);
            $this->data->set('data', $data);
            
            return;
        }
        
        $meta['error']     = V2Schedules::ERROR_NOT;
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

        $shallowCopy = function (ScheduleInterface $schedule) {
            $class = get_class($schedule);
            $newSchedule = new $class($schedule);
            $newSchedule->setDate($schedule->getDate());
            $newSchedule->setUsable($schedule->getUsable());
            $newSchedule->setHasPreSale($schedule->getHasPreSale());

            return $newSchedule;
        };

        foreach ($schedules->getSchedule() as $schedule) {
            $allSchedule = $shallowCopy($schedule);
            $allSchedules->add($allSchedule);
        }

        foreach ($preSchedules->getSchedule() as $schedule) {
            // 日付重複判定
            if ($allSchedules->has($schedule->getDate())) {
                $allSchedules->get($schedule->getDate())->setHasPreSale(true);
                
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
    
    /**
     * date action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     * @throws NotFoundException
     * @todo エラー系のレスポンス検討
     */
    public function executeDate($request, $response, $args)
    {
        $theaterName = $args['name'];
        $date = $args['date'];
        
        if (!TheaterSchedule::validate($theaterName)) {
            // ひとまずNotFoundとする SASAKI-338
            throw new NotFoundException($request, $response);
        }
        
        $useTestApi = $this->useTestApi();
        $theaterSchedule = new TheaterSchedule($theaterName, $useTestApi);
        
        $builer = new V2ScheduleBuilder($this->purchaseBaseUrl);
        $preBuiler = new V2PreScheduleBuilder($this->purchaseBaseUrl);
        
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

        if ($schedules->getError() === V2Schedules::ERROR_OTHER
            || $preSchedules->getError() === V2Schedules::ERROR_OTHER
        ) {
            throw new \RuntimeException('schedule unknown error');
        } elseif ($schedules->getError() === V2Schedules::ERROR_NO_CONTENT
            && $preSchedules->getError() === V2Schedules::ERROR_NO_CONTENT
        ) {
            $meta['error'] = V2Schedules::ERROR_NO_CONTENT;
            $this->data->set('meta', $meta);
            $this->data->set('data', $data);
            
            return;
        }
        
        $meta['error']     = V2Schedules::ERROR_NOT;
        $meta['attention'] = $schedules->getAttention(); // 通常、先行で同じ想定
        
        $params = [
            'date' => $date,
        ];
        $movieCollection = $this->findSchedule($params, $schedules, $preSchedules);

        foreach ($movieCollection as $movie) {
            $data[] = $movie->toArray();
        }
        
        $this->data->set('meta', $meta);
        $this->data->set('data', $data);
    }
    
    /**
     * スケジュール検索
     *
     * @param array              $params
     * @param SchedulesInterface $schedules
     * @param SchedulesInterface $preSchedules
     * @return MovieCollection
     */
    public function findSchedule(
        array $params,
        SchedulesInterface $schedules,
        SchedulesInterface $preSchedules
    ) {
        $movieCollection = new MovieCollection();

        // 先行販売の作品を優先するため、通常のを先にcollectionに追加する SASAKI-375
        $this->findMovie($params, $schedules, $movieCollection);

        $this->findMovie($params, $preSchedules, $movieCollection);

        return $movieCollection;
    }
    
    
    /**
     * 作品検索
     *
     * @param array              $params
     * @param SchedulesInterface $schedules
     * @param MovieCollection    $movieCollection
     */
    protected function findMovie(
        array $params,
        SchedulesInterface $schedules,
        MovieCollection $movieCollection
    ) {
        foreach ($schedules->getSchedule() as $schedule) {
            if ($schedule->getDate() !== $params['date']) {
                continue;
            }

            foreach ($schedule->getMovie() as $movie) {
                $movieCollection->add($movie);
            }

            break; // 同じ日付は無い想定
        }
    }
}
