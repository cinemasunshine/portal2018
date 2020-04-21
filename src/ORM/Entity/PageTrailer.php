<?php

/**
 * PageTrailer.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageTrailer entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="page_trailer", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class PageTrailer extends AbstractEntity
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
     * page
     *
     * @var Page
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="newsList")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $page;
    
    
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
     * page
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }
    
    /**
     * set page
     *
     * @param Page $page
     * @return void
     * @throws \LogicException
     */
    public function setPage(Page $page)
    {
        throw new \LogicException('Not allowed.');
    }
}
