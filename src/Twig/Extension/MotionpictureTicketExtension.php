<?php
/**
 * MotionpictureTicketExtension.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\ORM\Entity\Page;

/**
 * Motionpicture Ticket twig extension class
 */
class MotionpictureTicketExtension extends \Twig_Extension
{
    /** @var array */
    protected $settings;
    
    /**
     * construct
     * 
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }
    
    /**
     * get functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('mp_ticket_inquiry', [$this, 'getInquiryUrl'], [ 'is_safe' => ['html'] ]),
        ];
    }
    
    /**
     * return inquiry URL
     *
     * @param string $theaterCode
     * @return string
     */
    public function getInquiryUrl(string $theaterCode)
    {
        $path = sprintf('/inquiry/login?theater=%s', $theaterCode);
        
        return $this->settings['url'] . $path;
    }
}