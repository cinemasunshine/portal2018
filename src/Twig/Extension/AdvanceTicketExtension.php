<?php

namespace App\Twig\Extension;

use App\ORM\Entity\AdvanceTicket;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * AdvanceTicket twig extension class
 */
class AdvanceTicketExtension extends AbstractExtension
{
    /**
     * construct
     */
    public function __construct()
    {
    }

    /**
     * get functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('advance_ticket_type_label', [$this, 'getTypeLabel']),
        ];
    }

    /**
     * return type label
     *
     * @param int $type
     * @return string|null
     */
    public function getTypeLabel(int $type)
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
