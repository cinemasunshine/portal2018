<?php
/**
 * CommonExtension.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\ORM\Entity\Theater;

/**
 * Common twig extension class
 */
class CommonExtension extends \Twig_Extension
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
            new \Twig_Function('facebook', [$this, 'getFacebookUrl']),
            new \Twig_Function('twitter', [$this, 'getTwitterUrl']),
        ];
    }
    
    /**
     * retrun facebook URL
     *
     * @param string $name
     * @return string
     */
    public function getFacebookUrl(string $name)
    {
        return sprintf('https://www.facebook.com/%s', $name);
    }
    
    /**
     * return twitter URL
     *
     * @param string $name
     * @return string
     */
    public function getTwitterUrl(string $name)
    {
        return sprintf('https://twitter.com/%s', $name);
    }
    
    /**
     * get filters
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('weekday', [$this, 'weekdayFilter']),
        );
    }
    
    /**
     * return weekday
     *
     * @param \DateTime $datetime
     * @return void
     */
    public function weekdayFilter(\DateTime $datetime)
    {
        $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
        
        return $weekdays[$datetime->format('w')];
    }
}