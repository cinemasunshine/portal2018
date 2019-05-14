<?php
/**
 * TheaterOpeningHour.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

/**
 * TheaterOpeningHour entity class
 *
 * 現時点ではDoctrineのEntityとはしない。
 * DBへはjsonデータへ変換して文字列カラムへセットする。
 */
class TheaterOpeningHour extends AbstractEntity
{
    const TYPE_DATE = 1;
    const TYPE_TERM = 2;
    
    /**
     * type
     *
     * @var int
     */
    protected $type;
    
    /**
     * from_date
     *
     * @var \DateTime
     */
    protected $fromDate;
    
    /**
     * to_date
     *
     * @var \DateTime|null
     */
    protected $toDate;
    
    /**
     * time
     *
     * @var \DateTime
     */
    protected $time;
    
    
    /**
     * create
     *
     * @param array $array
     * @return self
     */
    public static function create(array $array)
    {
        $entity = new self;
        $entity->setType((int) $array['type']);
        $entity->setFromDate($array['from_date']);
        $entity->setToDate($array['to_date']);
        $entity->setTime($array['time']);
        
        return $entity;
    }
    
    
    /**
     * construct
     */
    public function __construct()
    {
    }
    
    /**
     * get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * set type
     *
     * @param int $type
     * @return void
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }
    
    /**
     * get from_date
     *
     * @return \DateTime
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }
    
    /**
     * set from_date
     *
     * @param \DateTime|string $fromDate
     * @return void
     */
    public function setFromDate($fromDate)
    {
        if ($fromDate instanceof \Datetime) {
            $this->fromDate = $fromDate;
        } else {
            $this->fromDate = new \DateTime($fromDate);
        }
    }
    
    /**
     * get to_date
     *
     * @return \DateTime|null
     */
    public function getToDate()
    {
        return $this->toDate;
    }
    
    /**
     * set to_date
     *
     * @param \DateTime|string|null $toDate
     * @return void
     */
    public function setToDate($toDate)
    {
        if (is_null($toDate) || $toDate instanceof \Datetime) {
            $this->toDate = $toDate;
        } else {
            $this->toDate = new \DateTime($toDate);
        }
    }
    
    /**
     * get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }
    
    /**
     * set time
     *
     * @param \DateTime|string $time
     * @return void
     */
    public function setTime($time)
    {
        if ($time instanceof \Datetime) {
            $this->time = $time;
        } else {
            $this->time = new \DateTime($time);
        }
    }
}
