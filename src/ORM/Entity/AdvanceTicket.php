<?php

declare(strict_types=1);

namespace App\ORM\Entity;

use Cinemasunshine\ORM\Entities\AdvanceSale as BaseAdvanceSale;
use Cinemasunshine\ORM\Entities\AdvanceTicket as BaseAdvanceTicket;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

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
     * @throws LogicException
     */
    public function __construct()
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setAdvanceSale(BaseAdvanceSale $advanceSale): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setPublishingStartDt($publishingStartDt): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setReleaseDt($releaseDt): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setReleaseDtText(?string $releaseDtText): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setIsSalesEnd(bool $isSalesEnd): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setType(int $type): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setPriceText(?string $priceText): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setSpecialGift(?string $specialGift): void
    {
        throw new LogicException('Not allowed.');
    }

    public function isSpecialGiftStock(int $stock): bool
    {
        return $this->getSpecialGiftStock() === $stock;
    }

    /**
     * 特典あり
     */
    public function isSpecialGiftStockIn(): bool
    {
        return $this->isSpecialGiftStock(self::SPECIAL_GIFT_STOCK_IN);
    }

    /**
     * 特典残り僅か
     */
    public function isSpecialGiftStockFew(): bool
    {
        return $this->isSpecialGiftStock(self::SPECIAL_GIFT_STOCK_FEW);
    }

    /**
     * 特典終了
     */
    public function isSpecialGiftStockNotIn(): bool
    {
        return $this->isSpecialGiftStock(self::SPECIAL_GIFT_STOCK_NOT_IN);
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setSpecialGiftStock($specialGiftStock): void
    {
        throw new LogicException('Not allowed.');
    }

    /**
     * {@inheritDoc}
     *
     * @throws LogicException
     */
    public function setSpecialGiftImage($specialGiftImage): void
    {
        throw new LogicException('Not allowed.');
    }
}
