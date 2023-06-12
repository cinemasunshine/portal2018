<?php

declare(strict_types=1);

namespace App\User\Provider;

use App\User\User;

class MembershipProvider implements ReadUserStateInterface
{
    private bool $authenticated;
    private ?User $user;

    public function __construct()
    {
        $this->authenticated = false;
        $this->user          = null;
    }

    public function login(): void
    {
        $this->authenticated = true;
        $this->user          = new User('', User::SERVICE_TYPE_MEMBERSHIP);
    }

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
