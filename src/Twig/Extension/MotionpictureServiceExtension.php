<?php
/**
 * MotionpictureServiceExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

/**
 * Motionpicture Service twig extension class
 */
class MotionpictureServiceExtension extends \Twig_Extension
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
            new \Twig_Function('mp_ticket_inquiry', [$this, 'getTicketInquiryUrl'], [ 'is_safe' => ['html'] ]),
        ];
    }

    /**
     * return ticket inquiry URL
     *
     * @param string $theaterCode
     * @return string
     */
    public function getTicketInquiryUrl(string $theaterCode): string
    {
        $path = sprintf('/inquiry/login?theater=%s', $theaterCode);

        return $this->settings['ticket_url'] . $path;
    }
}
