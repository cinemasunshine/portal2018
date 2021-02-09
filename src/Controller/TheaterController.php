<?php

declare(strict_types=1);

namespace App\Controller;

use App\ORM\Entity;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Theater controller
 */
class TheaterController extends BaseController
{
    /** @var Entity\Theater */
    protected $theater;

    /**
     * @param array<string, mixed> $args
     */
    protected function preExecute(Request $request, Response $response, array $args): void
    {
        /** @var Entity\Theater|null $theater */
        $theater = $this->getTheater($args['name']);

        if (is_null($theater)) {
            throw new NotFoundException($request, $response);
        }

        $this->theater = $theater;
    }

    /**
     * @param array<string, mixed> $args
     */
    protected function postExecute(Request $request, Response $response, array $args): void
    {
        $session = $this->sm->getContainer();

        /**
         * 閲覧した劇場ページ
         * ログイン、ログアウトのリダイレクト先として保存
         */
        $session['viewed_theater'] = $this->theater->getName();
    }

    protected function getTheater(string $name): ?Entity\Theater
    {
        return $this->em
            ->getRepository(Entity\Theater::class)
            ->findOneByName($name);
    }

    /**
     * index action
     *
     * @param array<string, mixed> $args
     */
    public function executeIndex(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $mainBanners = $this->getMainBanners($theater);

        $infoNewsList = $this->getNewsList(
            $theater,
            Entity\News::CATEGORY_INFO,
            8
        );

        if ($theater->isStatusClosed()) {
            return $this->render($response, 'theater/index/closed.html.twig', [
                'theater' => $theater,
                'mainBanners' => $mainBanners,
                'infoNewsList' => $infoNewsList,
            ]);
        }

        $trailer = $this->getTrailer($theater);

        $eventNewsList = $this->getNewsList(
            $theater,
            Entity\News::CATEGORY_EVENT,
            8
        );

        $newsList = $this->getNewsList(
            $theater,
            [
                Entity\News::CATEGORY_NEWS, // SASAKI-271
                Entity\News::CATEGORY_IMAX, // SASAKI-271
                Entity\News::CATEGORY_4DX, // SASAKI-271
                Entity\News::CATEGORY_SCREENX, // SASAKI-351
                Entity\News::CATEGORY_4DX_SCREEN, // SASAKI-482
            ],
            8
        );

        $campaigns = $this->getCampaigns($theater);

        return $this->render($response, 'theater/index/index.html.twig', [
            'theater' => $theater,
            'mainBanners' => $mainBanners,
            'infoNewsList' => $infoNewsList,
            'trailer' => $trailer,
            'eventNewsList' => $eventNewsList,
            'newsList' => $newsList,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * @return Entity\MainBanner[]
     */
    protected function getMainBanners(Entity\Theater $theater): array
    {
        return $this->em
            ->getRepository(Entity\MainBanner::class)
            ->findByTheaterId($theater->getId());
    }

    /**
     * @return Entity\Campaign[]
     */
    protected function getCampaigns(Entity\Theater $theater): array
    {
        return $this->em
            ->getRepository(Entity\Campaign::class)
            ->findByTheater($theater->getId());
    }

    protected function getTrailer(Entity\Theater $theater): ?Entity\Trailer
    {
        $trailers = $this->em
            ->getRepository(Entity\Trailer::class)
            ->findByTheater($theater->getId());

        if (count($trailers) === 0) {
            return null;
        }

        // シャッフルしてランダムに１件取得する
        shuffle($trailers);

        return $trailers[0];
    }

    /**
     * access action
     *
     * @param array<string, mixed> $args
     */
    public function executeAccess(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $infoNewsList = $this->getNewsList(
            $theater,
            Entity\News::CATEGORY_INFO,
            8
        );

        return $this->render($response, 'theater/access.html.twig', [
            'theater' => $theater,
            'infoNewsList' => $infoNewsList,
        ]);
    }

    /**
     * admission action
     *
     * @param array<string, mixed> $args
     */
    public function executeAdmission(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $campaigns = $this->getCampaigns($theater);

        return $this->render($response, 'theater/admission.html.twig', [
            'theater' => $theater,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * advance ticket action
     *
     * @param array<string, mixed> $args
     */
    public function executeAdvanceTicket(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $advanceTickets = $this->getAdvanceTickets($theater->getId());

        $campaigns = $this->getCampaigns($theater);

        $infoNewsList = $this->getNewsList(
            $theater,
            Entity\News::CATEGORY_INFO,
            8
        );

        return $this->render($response, 'theater/advance_ticket.html.twig', [
            'theater' => $theater,
            'advanceTickets' => $advanceTickets,
            'campaigns' => $campaigns,
            'infoNewsList' => $infoNewsList,
        ]);
    }

    /**
     * @return Entity\AdvanceTicket[]
     */
    protected function getAdvanceTickets(int $theaterId): array
    {
        return $this->em
            ->getRepository(Entity\AdvanceTicket::class)
            ->findByTheater($theaterId);
    }

    /**
     * concession action
     *
     * @param array<string, mixed> $args
     */
    public function executeConcession(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $campaigns = $this->getCampaigns($theater);

        $infoNewsList = $this->getNewsList(
            $theater,
            Entity\News::CATEGORY_INFO,
            8
        );

        return $this->render($response, 'theater/concession.html.twig', [
            'theater' => $theater,
            'campaigns' => $campaigns,
            'infoNewsList' => $infoNewsList,
        ]);
    }

    /**
     * floor guide action
     *
     * @param array<string, mixed> $args
     */
    public function executeFloorGuide(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $infoNewsList = $this->getNewsList(
            $theater,
            Entity\News::CATEGORY_INFO,
            8
        );

        return $this->render($response, 'theater/floor_guide.html.twig', [
            'theater' => $theater,
            'infoNewsList' => $infoNewsList,
        ]);
    }

    /**
     * news list action
     *
     * @param array<string, mixed> $args
     */
    public function executeNewsList(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $newsList = $this->getNewsList($theater);

        $campaigns = $this->getCampaigns($theater);

        return $this->render($response, 'theater/news/list.html.twig', [
            'theater' => $theater,
            'newsList' => $newsList,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * @param int[]|int $category
     * @return Entity\News[]
     */
    protected function getNewsList(Entity\Theater $theater, $category = [], ?int $limit = null): array
    {
        if (! is_array($category)) {
            $category = [$category];
        }

        return $this->em
            ->getRepository(Entity\News::class)
            ->findByTheater($theater->getId(), $category, $limit);
    }

    /**
     * news show action
     *
     * @param array<string, mixed> $args
     */
    public function executeNewsShow(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        /** @var Entity\News|null $news */
        $news = $this->em
            ->getRepository(Entity\News::class)
            ->findOneById((int) $args['id']);

        if (is_null($news)) {
            throw new NotFoundException($request, $response);
        }

        return $this->render($response, 'theater/news/show.html.twig', [
            'theater' => $theater,
            'news' => $news,
        ]);
    }
}
