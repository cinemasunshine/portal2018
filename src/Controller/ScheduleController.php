<?php

declare(strict_types=1);

namespace App\Controller;

use App\ORM\Entity\Schedule;
use App\ORM\Repository\ScheduleRepository;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class ScheduleController extends GeneralController
{
    protected function getScheduleRepository(): ScheduleRepository
    {
        return $this->em->getRepository(Schedule::class);
    }

    /**
     * @return Schedule[]
     */
    protected function findNowShowingSchedules(): array
    {
        return $this->getScheduleRepository()
            ->findNowShowing();
    }

    /**
     * @return Schedule[]
     */
    protected function findCommingSoonSchedules(): array
    {
        return $this->getScheduleRepository()
            ->findCommingSoon();
    }

    protected function findOneSchedule(int $id): ?Schedule
    {
        return $this->getScheduleRepository()
            ->findOneById($id);
    }

    /**
     * list action
     *
     * @param array<string, mixed> $args
     */
    public function executeList(Request $request, Response $response, array $args): Response
    {
        $theaters = $this->getTheaters();

        $nowShowingSchedules = $this->findNowShowingSchedules();

        $commingSoonSchedules = $this->findCommingSoonSchedules();

        return $this->render($response, 'schedule/list.html.twig', [
            'theaters' => $theaters,
            'nowShowingSchedules' => $nowShowingSchedules,
            'commingSoonSchedules' => $commingSoonSchedules,
        ]);
    }

    /**
     * show action
     *
     * @param array<string, mixed> $args
     */
    public function executeShow(Request $request, Response $response, array $args): Response
    {
        $schedule = $this->findOneSchedule((int) $args['schedule']);

        if (is_null($schedule)) {
            throw new NotFoundException($request, $response);
        }

        $theaters = $this->getTheaters();

        return $this->render($response, 'schedule/show.html.twig', [
            'schedule' => $schedule,
            'theaters' => $theaters,
        ]);
    }
}
