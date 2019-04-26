<?php
/**
 * TheaterNews.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TheaterNews entity class
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="theater_news", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class TheaterNews extends AbstractEntity
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
     * news
     *
     * @var News
     * @ORM\ManyToOne(targetEntity="News")
     * @ORM\JoinColumn(name="news_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $news;
    
    /**
     * theater
     *
     * @var Theater
     * @ORM\ManyToOne(targetEntity="Theater", inversedBy="theaters")
     * @ORM\JoinColumn(name="theater_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $theater;
    
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
     * get news
     *
     * @return News
     */
    public function getNews()
    {
        return $this->news;
    }
    
    /**
     * set news
     *
     * @param News $news
     * @return void
     * @throws \LogicException
     */
    public function setNews(News $news)
    {
        throw new \LogicException('Not allowed.');
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
