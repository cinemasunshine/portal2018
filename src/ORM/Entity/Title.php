<?php

/**
 * Title.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * Title entity class
 *
 * @ORM\Entity(readOnly=true)
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
     * @var File|null
     * @ORM\OneToOne(targetEntity="File", fetch="EAGER")
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
     * @var string|null
     * @ORM\Column(type="string", name="name_kana", nullable=true)
     */
    protected $nameKana;

    /**
     * name_original
     *
     * @var string|null
     * @ORM\Column(type="string", name="name_original", nullable=true)
     */
    protected $nameOriginal;

    /**
     * credit
     *
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $credit;

    /**
     * catchcopy
     *
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected $catchcopy;

    /**
     * introduction
     *
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected $introduction;

    /**
     * director
     *
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $director;

    /**
     * cast
     *
     * @var string|null
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
     * @var string|null
     * @ORM\Column(type="string", name="official_site", nullable=true)
     */
    protected $officialSite;

    /**
     * rating
     *
     * @var int|null
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned"=true})
     */
    protected $rating;

    /**
     * universal
     *
     * @var array|null
     * @ORM\Column(type="json", nullable=true)
     */
    protected $universal;

    /**
     * trailers
     *
     * @var Collection<Trailer>
     * @ORM\OneToMany(targetEntity="Trailer", mappedBy="title", indexBy="id")
     */
    protected $trailers;

    /**
     * campaigns
     *
     * @var Collection<Campaign>
     * @ORM\OneToMany(targetEntity="Campaign", mappedBy="title", indexBy="id")
     */
    protected $campaigns;

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
     * @return string|null
     */
    public function getNameKana()
    {
        return $this->nameKana;
    }

    /**
     * set name_kana
     *
     * @param string|null $nameKana
     * @return void
     * @throws \LogicException
     */
    public function setNameKana(?string $nameKana)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get name_original
     *
     * @return string|null
     */
    public function getNameOriginal()
    {
        return $this->nameOriginal;
    }

    /**
     * set name_original
     *
     * @param string|null $nameOriginal
     * @return void
     * @throws \LogicException
     */
    public function setNameOriginal(?string $nameOriginal)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * credit
     *
     * @return string|null
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * set credit
     *
     * @param string|null $credit
     * @return void
     * @throws \LogicException
     */
    public function setCredit(?string $credit)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get catchcopy
     *
     * @return string|null
     */
    public function getCatchcopy()
    {
        return $this->catchcopy;
    }

    /**
     * set catchcopy
     *
     * @param string|null $catchcopy
     * @return void
     * @throws \LogicException
     */
    public function setCatchcopy(?string $catchcopy)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get introduction
     *
     * @return string|null
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * set introduction
     *
     * @param string|null $introduction
     * @return void
     * @throws \LogicException
     */
    public function setIntroduction(?string $introduction)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get director
     *
     * @return string|null
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * set director
     *
     * @param string|null $director
     * @return void
     * @throws \LogicException
     */
    public function setDirector(?string $director)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get cast
     *
     * @return string|null
     */
    public function getCast()
    {
        return $this->cast;
    }

    /**
     * set cast
     *
     * @param string|null $cast
     * @return void
     * @throws \LogicException
     */
    public function setCast(?string $cast)
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
     * @return int|null
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * set rating
     *
     * @param int|null $rating
     * @return void
     * @throws \LogicException
     */
    public function setRating(?int $rating)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get universal
     *
     * @return array|null
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
     * @param array|null $universal
     * @return void
     * @throws \LogicException
     */
    public function setUniversal(?array $universal)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get trailers
     *
     * @return Collection
     */
    public function getTrailers()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('isDeleted', false))
            ->orderBy([ 'createdAt' => Criteria::DESC ]);

        return $this->trailers->matching($criteria);
    }

    /**
     * get campaigns
     *
     * 表示順管理は想定していない。
     * 作品に紐付けられたものを登録された順でよいとのこと。
     *
     * @return Collection
     */
    public function getCampaigns()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('isDeleted', false))
            ->andWhere(Criteria::expr()->lte('startDt', new \DateTime('now')))
            ->andWhere(Criteria::expr()->gt('endDt', new \DateTime('now')))
            ->orderBy([ 'createdAt' => Criteria::ASC ]);

        return $this->campaigns->matching($criteria);
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
