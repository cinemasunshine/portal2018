<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\GeneralController;
use App\ORM\Entity\Campaign;
use App\ORM\Entity\Theater;
use App\ORM\Repository\CampaignRepository;
use App\ORM\Repository\TheaterRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use ReflectionClass;
use Slim\Container;

/**
 * @coversDefaultClass \App\Controller\GeneralController
 */
final class GeneralControllerTest extends BaseTestCase
{
    protected function createGeneralControllerReflection(): ReflectionClass
    {
        return new ReflectionClass(GeneralController::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&GeneralController
     */
    protected function createGeneralControllerMock(Container $container)
    {
        return Mockery::mock(GeneralController::class, [$container]);
    }

    /**
     * @return MockInterface&LegacyMockInterface&CampaignRepository
     */
    protected function createCampaignRepositoryMock()
    {
        return Mockery::mock(CampaignRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&TheaterRepository
     */
    protected function createTheaterRepositoryMock()
    {
        return Mockery::mock(TheaterRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&Campaign
     */
    protected function createCampaignMock()
    {
        return Mockery::mock(Campaign::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&Theater
     */
    protected function createTheaterMock()
    {
        return Mockery::mock(Theater::class);
    }

    /**
     * @covers ::getCampaignRepository
     * @test
     * @testdox getCampaignRepositoryはエンティティCampaignのRepositoryを返す
     */
    public function testGetCampaignRepository(): void
    {
        $campaingRepositoryMock = $this->createCampaignRepositoryMock();

        $container = $this->createContainer();

        $container['em']
            ->shouldReceive('getRepository')
            ->once()
            ->with(Campaign::class)
            ->andReturn($campaingRepositoryMock);

        $generalControllerMock = $this->createGeneralControllerMock($container);

        $generalControllerRef = $this->createGeneralControllerReflection();

        $getCampaignRepositoryMethodRef = $generalControllerRef->getMethod('getCampaignRepository');
        $getCampaignRepositoryMethodRef->setAccessible(true);

        $this->assertEquals(
            $campaingRepositoryMock,
            $getCampaignRepositoryMethodRef->invoke($generalControllerMock)
        );
    }

    /**
     * @covers ::findCampaigns
     * @test
     * @testdox findCampaignsはエンティティCampaignのリストを返す
     */
    public function testFindCampaigns(): void
    {
        $generalControllerMock = $this->createGeneralControllerMock($this->createContainer());
        $generalControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $result = [$this->createCampaignMock()];
        $generalControllerMock
            ->shouldReceive('getCampaignRepository->findByPage')
            ->once()
            ->with(1)
            ->andReturn($result);

        $generalControllerRef = $this->createGeneralControllerReflection();

        $findCampaignsMethodRef = $generalControllerRef->getMethod('findCampaigns');
        $findCampaignsMethodRef->setAccessible(true);

        $this->assertEquals(
            $result,
            $findCampaignsMethodRef->invoke($generalControllerMock)
        );
    }

    /**
     * @covers ::getTheaterRepository
     * @test
     * @testdox getTheaterRepositoryはエンティティTheaterのRepositoryを返す
     */
    public function testGetTheaterRepository(): void
    {
        $theaterRepositoryMock = $this->createTheaterRepositoryMock();

        $container = $this->createContainer();

        $container['em']
            ->shouldReceive('getRepository')
            ->once()
            ->with(Theater::class)
            ->andReturn($theaterRepositoryMock);

        $generalControllerMock = $this->createGeneralControllerMock($container);

        $generalControllerRef = $this->createGeneralControllerReflection();

        $getTheaterRepositoryMethodRef = $generalControllerRef->getMethod('getTheaterRepository');
        $getTheaterRepositoryMethodRef->setAccessible(true);

        $this->assertEquals(
            $theaterRepositoryMock,
            $getTheaterRepositoryMethodRef->invoke($generalControllerMock)
        );
    }

    /**
     * @covers ::findTheaters
     * @test
     * @testdox findTheatersはエンティティTheaterのリストを返す
     */
    public function testFindTheaters(): void
    {
        $generalControllerMock = $this->createGeneralControllerMock($this->createContainer());
        $generalControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $result = [$this->createTheaterMock()];
        $generalControllerMock
            ->shouldReceive('getTheaterRepository->findByActive')
            ->once()
            ->with()
            ->andReturn($result);

        $generalControllerRef = $this->createGeneralControllerReflection();

        $findTheatersMethodRef = $generalControllerRef->getMethod('findTheaters');
        $findTheatersMethodRef->setAccessible(true);

        $this->assertEquals(
            $result,
            $findTheatersMethodRef->invoke($generalControllerMock)
        );
    }
}
