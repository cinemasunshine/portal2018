<?php

namespace App\Controller\API;

use App\Schedule\Builder\V3\Schedule as V3ScheduleBuilder;
use App\Schedule\Collection\Movie as MovieCollection;
use App\Schedule\Entity\V3\Time as TimeEntity;
use App\Schedule\Theater as TheaterSchedule;
use Cinemasunshine\Schedule\Entity\SchedulesInterface;
use Cinemasunshine\Schedule\Entity\V3\Schedules as V3Schedules;
use Cinemasunshine\Schedule\Response\Http as HttpResponse;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Schedule controller
 */
class ScheduleController extends BaseController
{
    /** @var string */
    protected $scheduleEnv;

    /** @var string */
    protected $purchaseBaseUrl;

    protected function preExecute(Request $request, Response $response, array $args): void
    {
        $settings          = $this->settings['schedule'];
        $this->scheduleEnv = $settings['env'];

        $this->purchaseBaseUrl = $this->settings['mp_service']['ticket_entrance_url'];
    }

    /**
     * index action
     *
     * @todo エラー系のレスポンス検討
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     *
     * @throws NotFoundException
     */
    public function executeIndex(Request $request, Response $response, array $args)
    {
        $theaterName = $args['name'];

        if (! TheaterSchedule::validate($theaterName)) {
            // ひとまずNotFoundとする SASAKI-338
            throw new NotFoundException($request, $response);
        }

        $theaterSchedule = new TheaterSchedule($theaterName, $this->scheduleEnv);
        $builer          = new V3ScheduleBuilder($this->purchaseBaseUrl);

        $scheduleResponse = $theaterSchedule->fetchSchedule($builer);

        if ($scheduleResponse instanceof HttpResponse) {
            $schedules = $scheduleResponse->getContents();
        } else {
            $schedules = $scheduleResponse;
        }

        $meta = [];
        $data = [];

        if ($schedules->getError() === V3Schedules::ERROR_OTHER) {
            throw new \RuntimeException('schedule unknown error');
        }

        if ($schedules->getError() === V3Schedules::ERROR_NO_CONTENT) {
            $meta['error'] = V3Schedules::ERROR_NO_CONTENT;

            return $response->withJson([
                'meta' => $meta,
                'data' => $data,
            ]);
        }

        $meta['error']     = V3Schedules::ERROR_NOT;
        $meta['attention'] = $schedules->getAttention();

        foreach ($schedules->getScheduleCollection() as $schedule) {
            $data[] = $schedule->toArray(false);
        }

        return $response->withJson([
            'meta' => $meta,
            'data' => $data,
        ]);
    }

    /**
     * date action
     *
     * @todo エラー系のレスポンス検討
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     *
     * @throws NotFoundException
     */
    public function executeDate(Request $request, Response $response, array $args)
    {
        $theaterName = $args['name'];
        $date        = $args['date'];

        if (! TheaterSchedule::validate($theaterName)) {
            // ひとまずNotFoundとする SASAKI-338
            throw new NotFoundException($request, $response);
        }

        $theaterSchedule = new TheaterSchedule($theaterName, $this->scheduleEnv);
        $builer          = new V3ScheduleBuilder($this->purchaseBaseUrl);

        $scheduleResponse = $theaterSchedule->fetchSchedule($builer);

        if ($scheduleResponse instanceof HttpResponse) {
            $schedules = $scheduleResponse->getContents();
        } else {
            $schedules = $scheduleResponse;
        }

        $meta = [];
        $data = [];

        if ($schedules->getError() === V3Schedules::ERROR_OTHER) {
            throw new \RuntimeException('schedule unknown error');
        }

        if ($schedules->getError() === V3Schedules::ERROR_NO_CONTENT) {
            $meta['error'] = V3Schedules::ERROR_NO_CONTENT;

            return $response->withJson([
                'meta' => $meta,
                'data' => $data,
            ]);
        }

        $meta['error']     = V3Schedules::ERROR_NOT;
        $meta['attention'] = $schedules->getAttention();

        $params = ['date' => $date];

        $movieCollection = $this->findSchedule($params, $schedules);

        $today  = new \DateTime(date('Y-m-d'));
        $target = new \DateTime($date);

        if ($target < $today) {
            $this->fixOvernight($movieCollection);
        }

        foreach ($movieCollection as $movie) {
            $data[] = $movie->toArray();
        }

        return $response->withJson([
            'meta' => $meta,
            'data' => $data,
        ]);
    }

    /**
     * スケジュール検索
     *
     * @param array              $params
     * @param SchedulesInterface $schedules
     * @return MovieCollection
     */
    public function findSchedule(array $params, SchedulesInterface $schedules)
    {
        $movieCollection = new MovieCollection();

        $this->findMovie($params, $schedules, $movieCollection);

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
        foreach ($schedules->getScheduleCollection() as $schedule) {
            if ($schedule->getDate() !== $params['date']) {
                continue;
            }

            foreach ($schedule->getMovieCollection() as $movie) {
                $movieCollection->add($movie);
            }

            break; // 同じ日付は無い想定
        }
    }

    /**
     * fix overnight
     *
     * @link https://m-p.backlog.jp/view/SASAKI-506
     *
     * @param MovieCollection $movieCollection
     */
    protected function fixOvernight(MovieCollection $movieCollection)
    {
        foreach ($movieCollection as $movie) {
            foreach ($movie->getScreenCollection() as $screen) {
                foreach ($screen->getTimeCollection() as $time) {
                    $start = (int) str_replace(':', '', $time->getStart());

                    // １時間前に予約終了することを考えると25時でも問題ないが、シンプルに24時で判定する
                    if ($start < 2400) {
                        continue;
                    }

                    // 上映開始時刻が翌日

                    if ($time->getAvailable() === TimeEntity::SEAT_AVAILABLE_CAN_RESERVATION) {
                        $time->setAvailable(TimeEntity::SEAT_AVAILABLE_CANNOT_RESERVATION);
                    } elseif ($time->getAvailable() === TimeEntity::SEAT_SLIGHTLY_CAN_RESERVATION) {
                        $time->setAvailable(TimeEntity::SEAT_SLIGHTLY_CANNOT_RESERVATION);
                    }
                }
            }
        }
    }
}
