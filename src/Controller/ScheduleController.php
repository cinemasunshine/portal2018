<?php

declare(strict_types=1);

namespace App\Controller;

use App\ORM\Entity\News;
use App\ORM\Entity\Schedule;
use App\ORM\Entity\Title;
use App\ORM\Repository\NewsRepository;
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

    protected function getNewsRepository(): NewsRepository
    {
        return $this->em->getRepository(News::class);
    }

    /**
     * @return News[]
     */
    protected function findNewsByTitle(Title $title, ?int $limit = null): array
    {
        return $this->getNewsRepository()
            ->findByTitleId($title->getId(), $limit);
    }

    protected function findOneNews(int $id): ?News
    {
        return $this->getNewsRepository()
            ->findOneById($id);
    }

    /**
     * list action
     *
     * @param array<string, mixed> $args
     */
    public function executeList(Request $request, Response $response, array $args): Response
    {
        $theaters = $this->findTheaters();

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

        $newsList = $this->findNewsByTitle($schedule->getTitle(), 8);

        $theaters = $this->findTheaters();

        return $this->render($response, 'schedule/show.html.twig', [
            'schedule' => $schedule,
            'newsList' => $newsList,
            'theaters' => $theaters,
        ]);
    }

    /**
     * news action
     *
     * @param array<string, mixed> $args
     */
    public function executeNews(Request $request, Response $response, array $args): Response
    {
        $schedule = $this->findOneSchedule((int) $args['schedule']);

        if (is_null($schedule)) {
            throw new NotFoundException($request, $response);
        }

        $newsList = $this->findNewsByTitle($schedule->getTitle());

        return $this->render($response, 'schedule/news/index.html.twig', [
            'schedule' => $schedule,
            'newsList' => $newsList,
        ]);
    }

    /**
     * news show action
     *
     * @param array<string, mixed> $args
     */
    public function executeNewsShow(Request $request, Response $response, array $args): Response
    {
        $schedule = $this->findOneSchedule((int) $args['schedule']);

        if (is_null($schedule)) {
            throw new NotFoundException($request, $response);
        }

        $news = $this->findOneNews((int) $args['news']);

        if (is_null($news)) {
            throw new NotFoundException($request, $response);
        }

        return $this->render($response, 'schedule/news/show.html.twig', [
            'schedule' => $schedule,
            'news' => $news,
        ]);
    }
}
