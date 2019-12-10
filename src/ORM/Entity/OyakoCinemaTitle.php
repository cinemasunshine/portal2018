<?php
/**
 * OyakoCinemaTitle.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * OyakoCinemaTitle entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="Cinemasunshine\Portal\ORM\Repository\OyakoCinemaTitleRepository")
 * @ORM\Table(name="oyako_cinema_title", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class OyakoCinemaTitle extends AbstractEntity
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
     * title
     *
     * @var Title
     * @ORM\ManyToOne(targetEntity="Title")
     * @ORM\JoinColumn(name="title_id", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     */
    protected $title;

    /**
     * title_url
     *
     * @var string
     * @ORM\Column(type="string", name="title_url")
     */
    protected $titleUrl;

    /**
     * oyako_cinema_schedules
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="OyakoCinemaSchedule", mappedBy="oyakoCinemaTitle", orphanRemoval=true)
     */
    protected $oyakoCinemaSchedules;

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
     * get title
     *
     * @return Title
     */
    public function getTitle(): Title
    {
        return $this->title;
    }

    /**
     * set title
     *
     * @param Title $title
     * @return void
     * @throws \LogicException
     */
    public function setTitle(Title $title)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get title_url
     *
     * @return string
     */
    public function getTitleUrl(): string
    {
        return $this->titleUrl;
    }

    /**
     * set title_url
     *
     * @param string $titleUrl
     * @return void
     * @throws \LogicException
     */
    public function setTitleUrl(string $titleUrl)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * get oyako_cinema_schedules
     *
     * @return Collection
     */
    public function getOyakoCinemaSchedules(): Collection
    {
        $criteria = Criteria::create()
            ->orderBy([ 'date' => Criteria::ASC ]);

        return $this->oyakoCinemaSchedules->matching($criteria);
    }
}
