<?php

declare(strict_types=1);

namespace App\Controller\Theater;

use App\ORM\Entity\Schedule;
use App\ORM\Entity\Theater;
use App\ORM\Repository\ScheduleRepository;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class ScheduleController extends BaseController
{
    protected function getScheduleRepository(): ScheduleRepository
    {
        return $this->em->getRepository(Schedule::class);
    }

    /**
     * @return Schedule[]
     */
    protected function findNowShowingSchedules(Theater $theater): array
    {
        return $this->getScheduleRepository()
            ->findNowShowingByTheaterId($theater->getId());
    }

    /**
     * @return Schedule[]
     */
    protected function findCommingSoonSchedules(Theater $theater): array
    {
        return $this->getScheduleRepository()
            ->findCommingSoonByTheaterId($theater->getId());
    }

    protected function findOneSchedule(int $scheduleId): ?Schedule
    {
        return $this->getScheduleRepository()
            ->findOneById($scheduleId);
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeIndex(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $nowShowingSchedules = $this->findNowShowingSchedules($theater);

        $commingSoonSchedules = $this->findCommingSoonSchedules($theater);

        return $this->render($response, 'theater/schedule/index.html.twig', [
            'theater' => $theater,
            'nowShowingSchedules' => $nowShowingSchedules,
            'commingSoonSchedules' => $commingSoonSchedules,
        ]);
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeShow(Request $request, Response $response, array $args): Response
    {
        $schedule = $this->findOneSchedule((int) $args['schedule']);

        if (is_null($schedule)) {
            throw new NotFoundException($request, $response);
        }

        return $this->render($response, 'theater/schedule/show.html.twig', [
            'theater' => $this->theater,
            'schedule' => $schedule,
        ]);
    }
}
