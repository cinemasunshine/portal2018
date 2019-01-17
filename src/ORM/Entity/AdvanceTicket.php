<?php
/**
 * AdvanceTicket.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdvanceTicket entity class
 * 
 * @ORM\Entity(repositoryClass="Cinemasunshine\Portal\ORM\Repository\AdvanceTicketRepository")
 * @ORM\Table(name="advance_ticket", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class AdvanceTicket extends AbstractEntity
{
    use SoftDeleteTrait;
    use TimestampableTrait;
    
    const TYPE_MVTK  = 1;
    const TYPE_PAPER = 2;
    
    const SPECIAL_GIFT_STOCK_IN     = 1;
    const SPECIAL_GIFT_STOCK_FEW    = 2;
    const SPECIAL_GIFT_STOCK_NOT_IN = 3;
    
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
     * advance_sale
     *
     * @var AdvanceSale
     * @ORM\ManyToOne(targetEntity="AdvanceSale")
     * @ORM\JoinColumn(name="advance_sale_id", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     */
    protected $advanceSale;
    
    /**
     * publishing_start_dt
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="publishing_start_dt")
     */
    protected $publishingStartDt;
    
    /**
     * release_dt
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="release_dt")
     */
    protected $releaseDt;
    
    /**
     * release_dt_text
     *
     * @var string
     * @ORM\Column(type="string", name="release_dt_text", nullable=true)
     */
    protected $releaseDtText;
    
    /**
     * is_sales_end
     *
     * @var bool
     * @ORM\Column(type="boolean", name="is_sales_end")
     */
    protected $isSalesEnd;
    
    /**
     * type
     *
     * @var int
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    protected $type;
    
    /**
     * price_text
     *
     * @var string
     * @ORM\Column(type="string", name="price_text", nullable=true)
     */
    protected $priceText;
    
    /**
     * special_gift
     *
     * @var string
     * @ORM\Column(type="string", name="special_gift", nullable=true)
     */
    protected $specialGift;
    
    /**
     * special_gift_stock
     *
     * @var int|null
     * @ORM\Column(type="smallint", name="special_gift_stock", nullable=true, options={"unsigned"=true})
     */
    protected $specialGiftStock;
    
    /**
     * special_gift_image
     *
     * @var File|null
     * @ORM\OneToOne(targetEntity="File")
     * @ORM\JoinColumn(name="special_gift_image", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
     */
    protected $specialGiftImage;
    
    
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
     * get advance_sale
     *
     * @return AdvanceSale
     */
    public function getAdvanceSale()
    {
        return $this->advanceSale;
    }
    
    /**
     * set advance_sale
     *
     * @param AdvanceSale $advanceSale
     * @return void
     * @throws \LogicException
     */
    public function setAdvanceSale(AdvanceSale $advanceSale)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get publishing_start_dt
     *
     * @return \DateTime
     */
    public function getPublishingStartDt()
    {
        return $this->publishingStartDt;
    }
    
    /**
     * set publishing_start_dt
     *
     * @param \DateTime|string $publishingStartDt
     * @return void
     * @throws \LogicException
     */
    public function setPublishingStartDt($publishingStartDt)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get release_dt
     *
     * @return \DateTime
     */
    public function getReleaseDt()
    {
        return $this->releaseDt;
    }
    
    /**
     * set release_dt
     *
     * @param \DateTime|string $releaseDt
     * @return void
     * @throws \LogicException
     */
    public function setReleaseDt($releaseDt)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get release_dt_text
     *
     * @return string
     */
    public function getReleaseDtText()
    {
        return $this->releaseDtText;
    }
    
    /**
     * set release_dt_text
     *
     * @param string $releaseDtText
     * @return void
     * @throws \LogicException
     */
    public function setReleaseDtText(string $releaseDtText)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get is_sales_end
     *
     * @return bool
     */
    public function getIsSalesEnd()
    {
        return $this->isSalesEnd;
    }
    
    /**
     * is salse end
     * 
     * alias getIsSalesEnd()
     *
     * @return bool
     */
    public function isSalseEnd()
    {
        return $this->getIsSalesEnd();
    }
    
    /**
     * set is_salse_end
     *
     * @param bool $isSalesEnd
     * @return void
     * @throws \LogicException
     */
    public function setIsSalesEnd(bool $isSalesEnd)
    {
        throw new \LogicException('Not allowed.');
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
     * @throws \LogicException
     */
    public function setType(int $type)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get price_text
     *
     * @return string
     */
    public function getPriceText()
    {
        return $this->priceText;
    }
    
    /**
     * set price_text
     *
     * @param string $priceText
     * @return void
     * @throws \LogicException
     */
    public function setPriceText(string $priceText)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get special_gift
     *
     * @return string
     */
    public function getSpecialGift()
    {
        return $this->specialGift;
    }
    
    /**
     * set special_gift
     *
     * @param string $specialGift
     * @return void
     * @throws \LogicException
     */
    public function setSpecialGift(string $specialGift)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get special_gift_stock
     *
     * @return int|null
     */
    public function getSpecialGiftStock()
    {
        return $this->specialGiftStock;
    }
    
    /**
     * is special gift stock
     *
     * @param int $stock
     * @return boolean
     */
    public function isSpecialGiftStock(int $stock)
    {
        return $this->getSpecialGiftStock() === $stock;
    }
    
    /**
     * 特典あり
     *
     * @return boolean
     */
    public function isSpecialGiftStockIn()
    {
        return $this->isSpecialGiftStock(self::SPECIAL_GIFT_STOCK_IN);
    }
    
    /**
     * 特典残り僅か
     *
     * @return boolean
     */
    public function isSpecialGiftStockFew()
    {
        return $this->isSpecialGiftStock(self::SPECIAL_GIFT_STOCK_FEW);
    }
    
    /**
     * 特典終了
     *
     * @return boolean
     */
    public function isSpecialGiftStockNotIn()
    {
        return $this->isSpecialGiftStock(self::SPECIAL_GIFT_STOCK_NOT_IN);
    }
    
    /**
     * set special_gift_stock
     *
     * @param int|null $specialGiftStock
     * @return void
     * @throws \LogicException
     */
    public function setSpecialGiftStock($specialGiftStock)
    {
        throw new \LogicException('Not allowed.');
    }
    
    /**
     * get special_gift_image
     *
     * @return File|null
     */
    public function getSpecialGiftImage()
    {
        return $this->specialGiftImage;
    }
    
    /**
     * set special_gift_image
     *
     * @param File|null $specialGiftImage
     * @return void
     * @throws \LogicException
     */
    public function setSpecialGiftImage($specialGiftImage)
    {
        throw new \LogicException('Not allowed.');
    }
}
