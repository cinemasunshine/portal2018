<?php

declare(strict_types=1);

namespace App\User;

use App\User\Provider\MembershipProvider;
use App\User\Provider\ReadUserStateInterface;
use App\User\Provider\RewardProvider;
use Slim\Http\Cookies;

class Manager
{
    private RewardProvider $rewardProvider;
    private ReadUserStateInterface $userState;

    public function __construct(
        RewardProvider $rewardProvider,
        MembershipProvider $membershipProvider,
        Cookies $cookies
    ) {
        $this->rewardProvider = $rewardProvider;
        $this->userState      = $rewardProvider;

        if (! $cookies->get('logined')) {
            return;
        }

        $membershipProvider->login();
        $this->userState = $membershipProvider;
    }

    public function getRewardProvider(): RewardProvider
    {
        return $this->rewardProvider;
    }

    public function getUserState(): ReadUserStateInterface
    {
        return $this->userState;
    }
}
