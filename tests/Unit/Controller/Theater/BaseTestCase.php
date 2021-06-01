<?php

declare(strict_types=1);

namespace Tests\Unit\Controller\Theater;

use App\ORM\Entity\Theater;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Tests\Unit\Controller\BaseTestCase as ControllerBaseTestCase;

class BaseTestCase extends ControllerBaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&Theater
     */
    protected function createTheaterMock()
    {
        return Mockery::mock(Theater::class);
    }
}
