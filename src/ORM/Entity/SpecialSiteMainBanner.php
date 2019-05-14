<?php
/**
 * SpecialSiteMainBanner.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpecialSiteMainBanner entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="special_site_main_banner", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class SpecialSiteMainBanner extends AbstractEntity
{
    use TimestampableTrait;
    
    /**
     * id
     *
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * main_banner
     *
     * @var MainBanner
     * @ORM\ManyToOne(targetEntity="MainBanner")
     * @ORM\JoinColumn(name="main_banner_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $mainBanner;
    
    /**
     * special_site
     *
     * @var SpecialSite
     * @ORM\ManyToOne(targetEntity="SpecialSite")
     * @ORM\JoinColumn(name="special_site_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $specialSite;
    
    /**
     * display_order
     *
     * @var int
     * @ORM\Column(type="smallint", name="display_order", options={"unsigned"=true})
     */
    protected $displayOrder;
    
    
    /**
     * construct
     *
     * @throws \LogicException
     */
    public function __construct()
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * get main_banner
     *
     * @return MainBanner
     */
    public function getMainBanner()
    {
        return $this->mainBanner;
    }
    
    /**
     * set main_banner
     *
     * @param MainBanner $mainBanner
     * @return void
     * @throws \LogicException
     */
    public function setMainBanner(MainBanner $mainBanner)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get special_site
     *
     * @return SpecialSite
     */
    public function getSpecialSite()
    {
        return $this->specialSite;
    }
    
    /**
     * set special_site
     *
     * @param SpecialSite $specialSite
     * @return void
     * @throws \LogicException
     */
    public function setSpecialSite(SpecialSite $specialSite)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get display_order
     *
     * @return int
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }
    
    /**
     * set display_order
     *
     * @param int $displayOrder
     * @return void
     * @throws \LogicException
     */
    public function setDisplayOrder(int $displayOrder)
    {
        throw new \LogicException('Not allowed.');
    }
}
