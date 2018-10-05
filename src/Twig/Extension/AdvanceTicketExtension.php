<?php
/**
 * AdvanceTicketExtension.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\ORM\Entity\AdvanceTicket;

/**
 * AdvanceTicket twig extension class
 */
class AdvanceTicketExtension extends \Twig_Extension
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
            new \Twig_Function('advance_ticket_type_label', [$this, 'getTypeLabel']),
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
        if ($type === AdvanceTicket::TYPE_MVTK) {
            return 'ムビチケ';
        } else if ($type === AdvanceTicket::TYPE_PAPER) {
            return '紙券';
        }
        
        throw null;
    }
}