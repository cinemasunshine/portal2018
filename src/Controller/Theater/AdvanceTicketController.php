<?php

declare(strict_types=1);

namespace App\Controller\Theater;

use App\ORM\Entity\AdvanceTicket;
use App\ORM\Entity\News;
use App\ORM\Entity\Theater;
use App\ORM\Repository\AdvanceTicketRepository;
use Slim\Http\Request;
use Slim\Http\Response;

class AdvanceTicketController extends BaseController
{
    protected function getAdvanceTicketRepository(): AdvanceTicketRepository
    {
        return $this->em->getRepository(AdvanceTicket::class);
    }

    /**
     * @return AdvanceTicket[]
     */
    protected function findAdvanceTickets(Theater $theater): array
    {
        return $this->getAdvanceTicketRepository()
            ->findByTheater($theater->getId());
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeIndex(Request $request, Response $response, array $args): Response
    {
        $theater = $this->theater;

        $advanceTickets = $this->findAdvanceTickets($theater);

        $campaigns = $this->findCampaigns($theater);

        $infoNewsList = $this->findNewsList($theater, [News::CATEGORY_INFO], 8);

        return $this->render($response, 'theater/advance_ticket.html.twig', [
            'theater' => $theater,
            'advanceTickets' => $advanceTickets,
            'campaigns' => $campaigns,
            'infoNewsList' => $infoNewsList,
        ]);
    }
}
