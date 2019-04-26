<?php
/**
 * TitleRanking.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TitleRanking entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="title_ranking", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class TitleRanking extends AbstractEntity
{
    use TimestampableTrait;
    
    /**
     * id
     *
     * @var int
     * @ORM\Id
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * from_date
     *
     * @var \DateTime|null
     * @ORM\Column(type="date", name="from_date", nullable=true)
     */
    protected $fromDate;
    
    /**
     * to_date
     *
     * @var \DateTime|null
     * @ORM\Column(type="date", name="to_date", nullable=true)
     */
    protected $toDate;
    
    /**
     * rank1_title
     *
     * @var Title|null
     * @ORM\ManyToOne(targetEntity="Title")
     * @ORM\JoinColumn(name="rank1_title_id", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
     */
    protected $rank1Title;
    
    /**
     * rank2_title
     *
     * @var Title|null
     * @ORM\ManyToOne(targetEntity="Title")
     * @ORM\JoinColumn(name="rank2_title_id", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
     */
    protected $rank2Title;
    
    /**
     * rank3_title
     *
     * @var Title|null
     * @ORM\ManyToOne(targetEntity="Title")
     * @ORM\JoinColumn(name="rank3_title_id", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
     */
    protected $rank3Title;
    
    /**
     * rank4_title
     *
     * @var Title|null
     * @ORM\ManyToOne(targetEntity="Title")
     * @ORM\JoinColumn(name="rank4_title_id", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
     */
    protected $rank4Title;
    
    /**
     * rank5_title
     *
     * @var Title|null
     * @ORM\ManyToOne(targetEntity="Title")
     * @ORM\JoinColumn(name="rank5_title_id", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
     */
    protected $rank5Title;
    
    
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
     * get from_date
     *
     * @return \DateTime|null
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }
    
    /**
     * set from_date
     *
     * @param \DateTime|string|null $fromDate
     * @return void
     * @throws \LogicException
     */
    public function setFromDate($fromDate)
    {
        throw new \LogicException('Not allowed.');
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
     * @throws \LogicException
     */
    public function setToDate($toDate)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get rank1_title
     *
     * @return Title|null
     */
    public function getRank1Title()
    {
        return $this->rank1Title;
    }
    
    /**
     * set rank1_title
     *
     * @param Title|null $title
     * @throws \LogicException
     */
    public function setRank1Title($title)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get rank2_title
     *
     * @return Title|null
     */
    public function getRank2Title()
    {
        return $this->rank2Title;
    }
    
    /**
     * set rank2_title
     *
     * @param Title|null $title
     * @return void
     * @throws \LogicException
     */
    public function setRank2Title($title)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get rank3_title
     *
     * @return Title|null
     */
    public function getRank3Title()
    {
        return $this->rank3Title;
    }
    
    /**
     * set rank3_title
     *
     * @param Title|null $title
     * @return void
     * @throws \LogicException
     */
    public function setRank3Title($title)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get rank4_title
     *
     * @return Title|null
     */
    public function getRank4Title()
    {
        return $this->rank4Title;
    }
    
    /**
     * set rank4_title
     *
     * @param Title|null $title
     * @return void
     * @throws \LogicException
     */
    public function setRank4Title($title)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get rank5_title
     *
     * @return Title|null
     */
    public function getRank5Title()
    {
        return $this->rank5Title;
    }
    
    /**
     * set rank5_title
     *
     * @param Title|null $title
     * @return void
     * @throws \LogicException
     */
    public function setRank5Title($title)
    {
        throw new \LogicException('Not allowed.');
    }
}
