<?php
/**
 * TheaterMeta.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TheaterMeta entity class
 * 
 * @ORM\Entity
 * @ORM\Table(name="theater_meta", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class TheaterMeta extends AbstractEntity
{
    use TimestampableTrait;
    
    /**
     * id
     * 
     * @var int
     * @ORM\Id
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;
    
    /**
     * theater
     * 
     * @var Theater
     * @ORM\OneToOne(targetEntity="Theater")
     * @ORM\JoinColumn(name="theater_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $theater;
    
    /**
     * opening_hours
     * 
     * @var array|null
     * @ORM\Column(type="json", name="opening_hours")
     */
    protected $openingHours;
    
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
     * get theater
     *
     * @return Theater
     */
    public function getTheater()
    {
        return $this->theater;
    }
    
    /**
     * set theater
     *
     * @param Theater $theater
     * @return void
     * @throws \LogicException
     */
    public function setTheater(Theater $theater)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get opening_hours
     *
     * @return TheaterOpeningHour[]
     */
    public function getOpeningHours()
    {
        $hours = [];
        
        if (is_array($this->openingHours)) {
            foreach ($this->openingHours as $hour) {
                $hours[] = TheaterOpeningHour::create($hour);
            }
        }
        
        return $hours;
    }
    
    /**
     * set opening_hours
     *
     * @param TheaterOpeningHour[] $openingHours
     * @return void
     * @throws \LogicException
     */
    public function setOpeningHours(array $openingHours)
    {
        throw new \LogicException('Not allowed.');
    }
}