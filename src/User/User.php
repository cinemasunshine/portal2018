<?php

declare(strict_types=1);

namespace App\User;

class User
{
    public const SERVICE_TYPE_REWRD      = 'reward';
    public const SERVICE_TYPE_MEMBERSHIP = 'membership';

    private string $name;
    private string $serviceType;

    public function __construct(string $name, string $serviceType)
    {
        $this->name        = $name;
        $this->serviceType = $serviceType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getServiceType(): string
    {
        return $this->serviceType;
    }
}
