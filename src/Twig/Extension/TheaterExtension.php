<?php
/**
 * TheaterExtension.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\ORM\Entity\Theater;

/**
 * Theater twig extension class
 */
class TheaterExtension extends \Twig_Extension
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
            new \Twig_Function('theater_area', [$this, 'theaterArea']),
        ];
    }
    
    /**
     * return theater area label
     *
     * @param int $area
     * @return string|null
     */
    public function theaterArea(int $area)
    {
        $areas = Theater::getAreas();
        
        return $areas[$area] ?? null;
    }
}