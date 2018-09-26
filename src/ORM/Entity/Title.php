<?php
/**
 * Title.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Title entity class
 * 
 * @ORM\Entity
 * @ORM\Table(name="title", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class Title extends AbstractEntity
{
    use SavedUserTrait;
    use SoftDeleteTrait;
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
     * image
     *
     * @var File
     * @ORM\OneToOne(targetEntity="File")
     * @ORM\JoinColumn(name="image_file_id", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
     */
    protected $image;
    
    /**
     * name
     * 
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;
    
    /** 
     * name_kana
     * 
     * @var string
     * @ORM\Column(type="string", name="name_kana")
     */
    protected $nameKana;
    
    /** 
     * name_original
     * 
     * @var string
     * @ORM\Column(type="string", name="name_original")
     */
    protected $nameOriginal;
    
    /**
     * credit
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $credit;
    
    /**
     * catchcopy
     *
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $catchcopy;
    
    /**
     * introduction
     *
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $introduction;
    
    /**
     * director
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $director;
    
    /**
     * cast
     *
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cast;
    
    /**
     * publishing_expected_date
     *
     * @var \DateTime|null
     * @ORM\Column(type="date", name="publishing_expected_date", nullable=true)
     */
    protected $publishingExpectedDate;
    
    /**
     * official_site
     *
     * @var string
     * @ORM\Column(type="string", name="official_site", nullable=true)
     */
    protected $officialSite;
    
    /**
     * rating
     *
     * @var string
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned"=true})
     */
    protected $rating;
    
    /**
     * universal
     *
     * @var array
     * @ORM\Column(type="json", nullable=true)
     */
    protected $universal;
    
    /**
     * レイティング区分
     *
     * @var array
     */
    protected static $ratingTypes = [
        '1' => 'G',
        '2' => 'PG12',
        '3' => 'R15+',
        '4' => 'R18+',
    ];
    
    /**
     * ユニバーサル区分
     *
     * @var array
     */
    protected static $universalTypes = [
        '1' => '音声上映',
        '2' => '字幕上映',
    ];
    
    
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
     * get image
     *
     * @return File|null
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * set image
     *
     * @param File|null $image
     * @return void
     * @throws \LogicException
     */
    public function setImage($image)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * set name
     *
     * @param string $name
     * @return void
     * @throws \LogicException
     */
    public function setName(string $name)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get name_kana
     *
     * @return string
     */
    public function getNameKana()
    {
        return $this->nameKana;
    }
    
    /**
     * set name_kana
     *
     * @param string $nameKana
     * @return void
     * @throws \LogicException
     */
    public function setNameKana(string $nameKana)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get name_original
     *
     * @return string
     */
    public function getNameOriginal()
    {
        return $this->nameOriginal;
    }
    
    /**
     * set name_original
     *
     * @param string $nameOriginal
     * @return void
     * @throws \LogicException
     */
    public function setNameOriginal(string $nameOriginal)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * credit
     *
     * @return string
     */
    public function getCredit()
    {
        return $this->credit;
    }
    
    /**
     * set credit
     *
     * @param string $credit
     * @return void
     * @throws \LogicException
     */
    public function setCredit(string $credit)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get catchcopy
     *
     * @return string
     */
    public function getCatchcopy()
    {
        return $this->catchcopy;
    }
    
    /**
     * set catchcopy
     *
     * @param string $catchcopy
     * @return void
     * @throws \LogicException
     */
    public function setCatchcopy(string $catchcopy)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get introduction
     *
     * @return string
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }
    
    /**
     * set introduction
     *
     * @param string $introduction
     * @return void
     * @throws \LogicException
     */
    public function setIntroduction(string $introduction)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get director
     *
     * @return string
     */
    public function getDirector()
    {
        return $this->director;
    }
    
    /**
     * set director
     *
     * @param string $director
     * @return void
     * @throws \LogicException
     */
    public function setDirector(string $director)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get cast
     *
     * @return string
     */
    public function getCast()
    {
        return $this->cast;
    }
    
    /**
     * set cast
     *
     * @param string $cast
     * @return void
     * @throws \LogicException
     */
    public function setCast(string $cast)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get publishing_expected_date
     *
     * @return \DateTime|null
     */
    public function getPublishingExpectedDate()
    {
        return $this->publishingExpectedDate;
    }
    
    /**
     * set publishing_dxpected_date
     *
     * @param \DateTime|string|null $publishingExpectedDate
     * @return void
     * @throws \LogicException
     */
    public function setPublishingExpectedDate($publishingExpectedDate)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get official_site
     *
     * @return string
     */
    public function getOfficialSite()
    {
        return $this->officialSite;
    }
    
    /**
     * set official_site
     *
     * @param string $officialSite
     * @return void
     * @throws \LogicException
     */
    public function setOfficialSite(string $officialSite)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }
    
    /**
     * set rating
     *
     * @param int $rating
     * @return void
     * @throws \LogicException
     */
    public function setRating(int $rating)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get universal
     *
     * @return array
     */
    public function getUniversal()
    {
        return $this->universal;
    }
    
    /**
     * get univarsal label
     *
     * @return array
     */
    public function getUniversalLabel()
    {
        $univarsal = $this->getUniversal();
        $types = self::getUniversalTypes();
        $labels = [];
        
        foreach ($univarsal as $value) {
            if (isset($types[$value])) {
                $labels[] = $types[$value];
            }
        }
        
        return $labels;
    }
    
    /**
     * set universal
     *
     * @param array $universal
     * @return void
     * @throws \LogicException
     */
    public function setUniversal(array $universal)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get レイティング区分
     *
     * @return array
     */
    public static function getRatingTypes()
    {
        return self::$ratingTypes;
    }
    
    /**
     * get ユニバーサル区分
     *
     * @return array
     */
    public static function getUniversalTypes()
    {
        return self::$universalTypes;
    }
}