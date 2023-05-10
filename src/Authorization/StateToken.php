<?php

declare(strict_types=1);

namespace App\Authorization;

class StateToken
{
    public static function genelate(string $name, string $salt = 'salt'): string
    {
        return md5($salt . uniqid((string) random_int(1, 99999), true) . $name);
    }
}
