<?php

declare(strict_types=1);

namespace App\Schedule\Entity;

/**
 * EntityInterface
 */
interface PortalEntityInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
