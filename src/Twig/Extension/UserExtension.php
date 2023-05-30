<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\User\Provider\ReadUserStateInterface;
use App\User\User;
use LogicException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    private ReadUserStateInterface $userState;

    public function __construct(ReadUserStateInterface $userState)
    {
        $this->userState = $userState;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_login', [$this, 'isLogin'], [ 'is_safe' => ['all'] ]),
            new TwigFunction('user_name', [$this, 'getUserName'], [ 'is_safe' => ['all'] ]),
            new TwigFunction('is_reward_user', [$this, 'isRewardUser'], [ 'is_safe' => ['all'] ]),
            new TwigFunction('is_membership_user', [$this, 'isMembershipUser'], [ 'is_safe' => ['all'] ]),
        ];
    }

    public function isLogin(): bool
    {
        return $this->userState->isAuthenticated();
    }

    public function getUserName(): string
    {
        $user = $this->userState->getUser();

        return $user ? $user->getName() : '';
    }

    public function isRewardUser(): bool
    {
        $user = $this->userState->getUser();

        if (! $user) {
            throw new LogicException('Not authenticated');
        }

        return $user->getServiceType() === User::SERVICE_TYPE_REWRD;
    }

    public function isMembershipUser(): bool
    {
        $user = $this->userState->getUser();

        if (! $user) {
            throw new LogicException('Not authenticated');
        }

        return $user->getServiceType() === User::SERVICE_TYPE_MEMBERSHIP;
    }
}
