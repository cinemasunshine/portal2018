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
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="showing_format", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class ShowingFormat extends AbstractEntity
{
    use TimestampableTrait;

    public const SYSTEM_2D         = 1;
    public const SYSTEM_3D         = 2;
    public const SYSTEM_4DX        = 3;
    public const SYSTEM_4DX3D      = 4;
    public const SYSTEM_IMAX       = 5;
    public const SYSTEM_IMAX3D     = 6;
    // const SYSTEM_BESTIA           = 7; 削除 SASAKI-449
    // const SYSTEM_BESTIA3D         = 8; 削除 SASAKI-449
    // const SYSTEM_BTSX             = 9; 削除 SASAKI-449
    public const SYSTEM_SCREENX    = 10; // SASAKI-351
    public const SYSTEM_4DX_SCREEN = 11; // SASAKI-428、SASAKI-531
    public const SYSTEM_NONE       = 99;

    public const SOUND_BESTIA        = 1;
    public const SOUND_DTSX          = 2;
    public const SOUND_DOLBY_ATMOS   = 3;
    public const SOUND_GDC_IMMERSIVE = 4;
    public const SOUND_NONE          = 99;

    public const VOICE_SUBTITLE = 1;
    public const VOICE_DUB = 2;
    public const VOICE_NONE = 3;

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
     * sound
     *
     * @var int
     * @ORM\Column(type="smallint", nullable=false, options={"unsigned"=true})
     */
    protected $sound;

    /**
     * voice
     *
     * @var int
     * @ORM\Column(type="smallint", nullable=false, options={"unsigned"=true})
     */
    protected $voice;

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
     * get sound
     *
     * @return int
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * set sound
     *
     * @param int $sound
     * @return void
     * @throws \LogicException
     */
    public function setSound(int $sound)
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
