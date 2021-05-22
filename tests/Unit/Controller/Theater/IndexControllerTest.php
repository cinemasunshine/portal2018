<?php

declare(strict_types=1);

namespace Tests\Unit\Controller\Theater;

use App\Controller\Theater\IndexController;
use App\ORM\Entity\MainBanner;
use App\ORM\Entity\Trailer;
use App\ORM\Repository\MainBannerRepository;
use App\ORM\Repository\TrailerRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use ReflectionClass;
use Slim\Container;

/**
 * @coversDefaultClass \App\Controller\Theater\IndexController
 */
final class IndexControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&IndexController
     */
    protected function createIndexControllerMock(Container $container)
    {
        return Mockery::mock(IndexController::class, [$container]);
    }

    public function createIndexControllerReflection(): ReflectionClass
    {
        return new ReflectionClass(IndexController::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&MainBannerRepository
     */
    protected function createMainBannerRepositoryMock()
    {
        return Mockery::mock(MainBannerRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&MainBanner
     */
    protected function createMainBannerMock()
    {
        return Mockery::mock(MainBanner::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&TrailerRepository
     */
    protected function createTrailerRepository()
    {
        return Mockery::mock(TrailerRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&Trailer
     */
    protected function createTrailerMock()
    {
        return Mockery::mock(Trailer::class);
    }

    /**
     * @covers ::getMainBannerRepository
     * @test
     * @testdox getMainBannerRepositoryはエンティティMainBannerのRepositoryを取得できる
     */
    public function testGetMainBannerRepository(): void
    {
        $mainBannerRepositoryMock = $this->createMainBannerRepositoryMock();

        $container = $this->createContainer();

        $container['em']
            ->shouldReceive('getRepository')
            ->once()
            ->with(MainBanner::class)
            ->andReturn($mainBannerRepositoryMock);

        $indexControllerMock = $this->createIndexControllerMock($container);

        $indexControllerRef = $this->createIndexControllerReflection();

        $getMainBannerRepositoryMethodRef = $indexControllerRef->getMethod('getMainBannerRepository');
        $getMainBannerRepositoryMethodRef->setAccessible(true);

        $this->assertEquals(
            $mainBannerRepositoryMock,
            $getMainBannerRepositoryMethodRef->invoke($indexControllerMock)
        );
    }

    /**
     * @covers ::findMainBanners
     * @test
     * @testdox findMainBannersはエンティティTheaterを引数に、エンティティMainBannerのリストを取得できる
     */
    public function testFindMainBanners(): void
    {
        $theaterId = 1;

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->with()
            ->andReturn($theaterId);

        $result = [$this->createMainBannerMock()];

        $mainBannerRepositoryMock = $this->createMainBannerRepositoryMock();
        $mainBannerRepositoryMock
            ->shouldReceive('findByTheaterId')
            ->once()
            ->with($theaterId)
            ->andReturn($result);

        $indexControllerMock = $this->createIndexControllerMock($this->createContainer());
        $indexControllerMock->shouldAllowMockingProtectedMethods();

        $indexControllerMock
            ->shouldReceive('getMainBannerRepository')
            ->once()
            ->with()
            ->andReturn($mainBannerRepositoryMock);

        $indexControllerRef = $this->createIndexControllerReflection();

        $findMainBannersMethodRef = $indexControllerRef->getMethod('findMainBanners');
        $findMainBannersMethodRef->setAccessible(true);

        $this->assertEquals(
            $result,
            $findMainBannersMethodRef->invoke($indexControllerMock, $theaterMock)
        );
    }

    /**
     * @covers ::getTrailerRepository
     * @test
     * @testdox getTrailerRepositoryはエンティティTrailerのRepositoryを取得できる
     */
    public function testGetTrailerRepository(): void
    {
        $trailerRepositoryMock = $this->createTrailerRepository();

        $container = $this->createContainer();

        $container['em']
            ->shouldReceive('getRepository')
            ->once()
            ->with(Trailer::class)
            ->andReturn($trailerRepositoryMock);

        $indexControllerMock = $this->createIndexControllerMock($container);

        $indexControllerRef = $this->createIndexControllerReflection();

        $getTrailerRepositoryMethodRef = $indexControllerRef->getMethod('getTrailerRepository');
        $getTrailerRepositoryMethodRef->setAccessible(true);

        $this->assertEquals(
            $trailerRepositoryMock,
            $getTrailerRepositoryMethodRef->invoke($indexControllerMock)
        );
    }

    /**
     * @covers ::findOneTrailer
     * @test
     * @testdox findOneTrailerはTrailerが存在する場合、その中からランダムに1件返す
     */
    public function testFindOneTrailerCaseExists(): void
    {
        $theaterId = 1;

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->once()
            ->with()
            ->andReturn($theaterId);

        $trailers = array_fill(0, 3, $this->createTrailerMock());

        $trailerRepositoryMock = $this->createTrailerRepository();
        $trailerRepositoryMock
            ->shouldReceive('findByTheater')
            ->once()
            ->with($theaterId)
            ->andReturn($trailers);

        $indexControllerMock = $this->createIndexControllerMock($this->createContainer());
        $indexControllerMock->shouldAllowMockingProtectedMethods();

        $indexControllerMock
            ->shouldReceive('getTrailerRepository')
            ->once()
            ->with()
            ->andReturn($trailerRepositoryMock);

        $indexControllerRef = $this->createIndexControllerReflection();

        $findOneTrailerMethodRef = $indexControllerRef->getMethod('findOneTrailer');
        $findOneTrailerMethodRef->setAccessible(true);

        $this->assertContains(
            $findOneTrailerMethodRef->invoke($indexControllerMock, $theaterMock),
            $trailers
        );
    }

    /**
     * @covers ::findOneTrailer
     * @test
     * @testdox findOneTrailerはTrailerが存在しない場合、nullを返す
     */
    public function testFindOneTrailerCaseNotExists(): void
    {
        $theaterId = 1;

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->once()
            ->with()
            ->andReturn($theaterId);

        $trailerRepositoryMock = $this->createTrailerRepository();
        $trailerRepositoryMock
            ->shouldReceive('findByTheater')
            ->once()
            ->with($theaterId)
            ->andReturn([]);

        $indexControllerMock = $this->createIndexControllerMock($this->createContainer());
        $indexControllerMock->shouldAllowMockingProtectedMethods();

        $indexControllerMock
            ->shouldReceive('getTrailerRepository')
            ->once()
            ->with()
            ->andReturn($trailerRepositoryMock);

        $indexControllerRef = $this->createIndexControllerReflection();

        $findOneTrailerMethodRef = $indexControllerRef->getMethod('findOneTrailer');
        $findOneTrailerMethodRef->setAccessible(true);

        $this->assertNull($findOneTrailerMethodRef->invoke($indexControllerMock, $theaterMock));
    }

    /**
     * @covers ::executeShow
     * @test
     * @testdox executeShowは劇場のステータスが閉館の場合、executeShowClosedを実行する
     */
    public function testExecuteShowCaseTheaterClosed(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $indexControllerMock = $this->createIndexControllerMock($this->createContainer());
        $indexControllerMock->shouldAllowMockingProtectedMethods();
        $indexControllerMock
            ->shouldReceive('executeShowClosed')
            ->once()
            ->with($requestMock, $responseMock, $args)
            ->andReturn($responseMock);

        $indexControllerRef = $this->createIndexControllerReflection();

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('isStatusClosed')
            ->once()
            ->with()
            ->andReturn(true);

        $theaterPropertyRef = $indexControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($indexControllerMock, $theaterMock);

        $executeShowMethodRef = $indexControllerRef->getMethod('executeShow');
        $executeShowMethodRef->setAccessible(true);

        $this->assertEquals(
            $responseMock,
            $executeShowMethodRef->invoke($indexControllerMock, $requestMock, $responseMock, $args)
        );
    }

    /**
     * @covers ::executeShow
     * @test
     * @testdox executeShowは劇場のステータスがオープンの場合、executeShowOpenを実行する
     */
    public function testExecuteShowCaseTheaterNotClosed(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $indexControllerMock = $this->createIndexControllerMock($this->createContainer());
        $indexControllerMock->shouldAllowMockingProtectedMethods();
        $indexControllerMock
            ->shouldReceive('executeShowOpen')
            ->once()
            ->with($requestMock, $responseMock, $args)
            ->andReturn($responseMock);

        $indexControllerRef = $this->createIndexControllerReflection();

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('isStatusClosed')
            ->once()
            ->with()
            ->andReturn(false);

        $theaterPropertyRef = $indexControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($indexControllerMock, $theaterMock);

        $executeShowMethodRef = $indexControllerRef->getMethod('executeShow');
        $executeShowMethodRef->setAccessible(true);

        $this->assertEquals(
            $responseMock,
            $executeShowMethodRef->invoke($indexControllerMock, $requestMock, $responseMock, $args)
        );
    }
}
