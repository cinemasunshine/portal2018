<?php
/**
 * SpecialSiteTrailer.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpecialSiteTrailer entity class
 * 
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="special_site_trailer", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class SpecialSiteTrailer extends AbstractEntity
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
     * trailer
     *
     * @var Trailer
     * @ORM\ManyToOne(targetEntity="Trailer")
     * @ORM\JoinColumn(name="trailer_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $trailer;
    
    /**
     * special_site
     *
     * @var SpecialSite
     * @ORM\ManyToOne(targetEntity="SpecialSite")
     * @ORM\JoinColumn(name="special_site_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $specialSite;
    
    
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
     * get trailer
     *
     * @return Trailer
     */
    public function getTrailer()
    {
        return $this->trailer;
    }
    
    /**
     * set trailer
     *
     * @param Trailer $trailer
     * @return void
     * @throws \LogicException
     */
    public function setTrailer(Trailer $trailer)
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
}
