<?php
/**
 * ShowingFormat.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShowingFormat entity class
 * 
 * @ORM\Entity
 * @ORM\Table(name="showing_format", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class ShowingFormat extends AbstractEntity
{
    use TimestampableTrait;
    
    /** @var array */
    protected static $systemList = [
        1 => '2D',
        2 => '3D',
        3 => '4DX',
        4 => '4DX3D',
        5 => 'IMAX',
        6 => 'IMAX3D',
        7 => 'BESTIA',
        8 => 'BESTIA3D',
        9 => 'dts-X',
    ];
    
    /** @var array */
    protected static $voiceList = [
        1 => '字幕',
        2 => '吹替',
    ];
    
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
     * schedule
     *
     * @var Schedule
     * @ORM\ManyToOne(targetEntity="Schedule")
     * @ORM\JoinColumn(name="schedule_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $schedule;
    
    /**
     * system
     *
     * @var int
     * @ORM\Column(type="smallint", nullable=false, options={"unsigned"=true})
     */
    protected $system;
    
    /**
     * voice
     *
     * @var int
     * @ORM\Column(type="smallint", nullable=false, options={"unsigned"=true})
     */
    protected $voice;
    
    
    /**
     * return system list
     *
     * @return array
     */
    public static function getSystemList()
    {
        return self::$systemList;
    }
    
    /**
     * return voice list
     *
     * @return array
     */
    public static function getVoiceList()
    {
        return self::$voiceList;
    }
    
    
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
     * get schedule
     *
     * @return Schedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }
    
    /**
     * schedule
     *
     * @param Schedule $schedule
     * @return void
     * @throws \LogicException
     */
    public function setSchedule(Schedule $schedule)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get system
     *
     * @return int
     */
    public function getSystem()
    {
        return $this->system;
    }
    
    /**
     * get system label
     *
     * @return string|null
     */
    public function getSystemLabel()
    {
        return self::$systemList[$this->getSystem()] ?? null;
    }
    
    /**
     * set system
     *
     * @param int $system
     * @return void
     * @throws \LogicException
     */
    public function setSystem(int $system)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get voice
     *
     * @return int
     */
    public function getVoice()
    {
        return $this->voice;
    }
    
    /**
     * get voice label
     *
     * @return string|null
     */
    public function getVoiceLabel()
    {
        return self::$voiceList[$this->getVoice()] ?? null;
    }
    
    /**
     * set voice
     *
     * @param int $voice
     * @return void
     * @throws \LogicException
     */
    public function setVoice(int $voice)
    {
        throw new \LogicException('Not allowed.');
    }
}