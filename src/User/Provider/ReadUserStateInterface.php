<?php

declare(strict_types=1);

namespace App\User\Provider;

use App\User\User;

interface ReadUserStateInterface
{
    public function isAuthenticated(): bool;

    public function getUser(): ?User;
}
