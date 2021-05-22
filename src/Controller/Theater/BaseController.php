<?php

declare(strict_types=1);

namespace App\Controller\Theater;

use App\Controller\BaseController as AppBaseController;
use App\ORM\Entity\Campaign;
use App\ORM\Entity\News;
use App\ORM\Entity\Theater;
use App\ORM\Repository\CampaignRepository;
use App\ORM\Repository\NewsRepository;
use App\ORM\Repository\TheaterRepository;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class BaseController extends AppBaseController
{
    /** @var Theater */
    protected $theater;

    protected function getTheaterRepository(): TheaterRepository
    {
        return $this->em->getRepository(Theater::class);
    }

    protected function findOneTheaterByName(string $name): ?Theater
    {
        return $this->getTheaterRepository()
            ->findOneByName($name);
    }

    protected function getCampaignRepository(): CampaignRepository
    {
        return $this->em->getRepository(Campaign::class);
    }

    /**
     * @return Campaign[]
     */
    protected function findCampaigns(Theater $theater): array
    {
        return $this->getCampaignRepository()
            ->findByTheater($theater->getId());
    }

    protected function getNewsRepository(): NewsRepository
    {
        return $this->em->getRepository(News::class);
    }

    /**
     * @param int[] $categories
     * @return News[]
     */
    protected function findNewsList(Theater $theater, array $categories = [], ?int $limit = null): array
    {
        return $this->getNewsRepository()
            ->findByTheater($theater->getId(), $categories, $limit);
    }

    /**
     * @param array<string, mixed> $args
     */
    protected function preExecute(Request $request, Response $response, array $args): void
    {
        $theater = $this->findOneTheaterByName($args['name']);

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
}
