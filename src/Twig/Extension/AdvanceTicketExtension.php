<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\ORM\Entity\AdvanceTicket;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * AdvanceTicket twig extension class
 */
class AdvanceTicketExtension extends AbstractExtension
{
    public function __construct()
    {
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('advance_ticket_type_label', [$this, 'getTypeLabel']),
        ];
    }

    public function getTypeLabel(int $type): ?string
    {
        switch ($type) {
            case AdvanceTicket::TYPE_MVTK:
                return 'ムビチケカード';

            case AdvanceTicket::TYPE_PAPER:
                return '紙券';

            default:
                return null;
        }
    }
}
