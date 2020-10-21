<?php

/**
 * AdvanceTicket.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\AdvanceSale as BaseAdvanceSale;
use Cinemasunshine\ORM\Entities\AdvanceTicket as BaseAdvanceTicket;
use Doctrine\ORM\Mapping as ORM;

/**
 * AdvanceTicket entity class
 *
 * @ORM\Entity(readOnly=true, repositoryClass="App\ORM\Repository\AdvanceTicketRepository")
 * @ORM\Table(name="advance_ticket", options={"collate"="utf8mb4_general_ci"})
 * @ORM\HasLifecycleCallbacks
 */
class AdvanceTicket extends BaseAdvanceTicket
{
    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function __construct()
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setAdvanceSale(BaseAdvanceSale $advanceSale)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setPublishingStartDt($publishingStartDt)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setReleaseDt($releaseDt)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setReleaseDtText(?string $releaseDtText)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setIsSalesEnd(bool $isSalesEnd)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setType(int $type)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setPriceText(?string $priceText)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setSpecialGift(?string $specialGift)
    {
        throw new \LogicException('Not allowed.');
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
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setSpecialGiftStock($specialGiftStock)
    {
        throw new \LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException
     */
    public function setSpecialGiftImage($specialGiftImage)
    {
        throw new \LogicException('Not allowed.');
    }
}
