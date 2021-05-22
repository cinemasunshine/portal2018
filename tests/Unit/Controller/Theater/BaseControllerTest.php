<?php

declare(strict_types=1);

namespace Tests\Unit\Controller\Theater;

use App\Controller\Theater\BaseController;
use App\ORM\Entity\Campaign;
use App\ORM\Entity\News;
use App\ORM\Entity\Theater;
use App\ORM\Repository\CampaignRepository;
use App\ORM\Repository\NewsRepository;
use App\ORM\Repository\TheaterRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use ReflectionClass;
use Slim\Container;
use Slim\Exception\NotFoundException;

/**
 * @coversDefaultClass \App\Controller\Theater\BaseController
 */
final class BaseControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&BaseController
     */
    protected function createBaseControllerMock(Container $container)
    {
        return Mockery::mock(BaseController::class, [$container]);
    }

    protected function createBaseControllerReflection(): ReflectionClass
    {
        return new ReflectionClass(BaseController::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&TheaterRepository
     */
    protected function createTheaterRepositoryMock()
    {
        return Mockery::mock(TheaterRepository::class);
    }

    /**
     * @covers ::getTheaterRepository
     * @test
     * @testdox getTheaterRepositoryはエンティティTheaterのRepositoryを取得できる
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

        $baseControllerMock = $this->createBaseControllerMock($container);

        $baseControllerRef = $this->createBaseControllerReflection();

        $getTheaterRepositoryMethodRef = $baseControllerRef->getMethod('getTheaterRepository');
        $getTheaterRepositoryMethodRef->setAccessible(true);

        $this->assertEquals(
            $theaterRepositoryMock,
            $getTheaterRepositoryMethodRef->invoke($baseControllerMock)
        );
    }

    /**
     * @return array<string,array{string,?Theater}>
     */
    public function findOneTheaterDataProvider(): array
    {
        return [
            'theater exists' => [
                'match',
                Mockery::mock(Theater::class),
            ],
            'theater dose not exists' => [
                'unmatch',
                null,
            ],
        ];
    }

    /**
     * @covers ::findOneTheaterByName
     * @dataProvider findOneTheaterDataProvider
     * @test
     * @testdox findOneTheaterByNameは引数のnameにマッチするTheaterを取得できる
     */
    public function testFindOneTheater(string $name, ?Theater $result): void
    {
        $theaterRepositoryMock = $this->createTheaterRepositoryMock();
        $theaterRepositoryMock
            ->shouldReceive('findOneByName')
            ->once()
            ->with($name)
            ->andReturn($result);

        $baseControllerMock = $this->createBaseControllerMock($this->createContainer());
        $baseControllerMock->shouldAllowMockingProtectedMethods();

        $baseControllerMock
            ->shouldReceive('getTheaterRepository')
            ->once()
            ->with()
            ->andReturn($theaterRepositoryMock);

        $baseControllerRef = $this->createBaseControllerReflection();

        $findOneTheaterByNameMethodRef = $baseControllerRef->getMethod('findOneTheaterByName');
        $findOneTheaterByNameMethodRef->setAccessible(true);

        $this->assertEquals(
            $result,
            $findOneTheaterByNameMethodRef->invoke($baseControllerMock, $name)
        );
    }

    /**
     * @return MockInterface&LegacyMockInterface&CampaignRepository
     */
    protected function createCampaignRepositoryMock()
    {
        return Mockery::mock(CampaignRepository::class);
    }

    /**
     * @covers ::getCampaignRepository
     * @test
     * @testdox getCampaignRepositoryはエンティティCampaignのRepositoryを取得できる
     */
    public function testGetCampaignRepository(): void
    {
        $campaignRepositoryMock = $this->createCampaignRepositoryMock();

        $container = $this->createContainer();

        $container['em']
            ->shouldReceive('getRepository')
            ->once()
            ->with(Campaign::class)
            ->andReturn($campaignRepositoryMock);

        $baseControllerMock = $this->createBaseControllerMock($container);

        $baseControllerRef = $this->createBaseControllerReflection();

        $getCampaignRepositoryMethodRef = $baseControllerRef->getMethod('getCampaignRepository');
        $getCampaignRepositoryMethodRef->setAccessible(true);

        $this->assertEquals(
            $campaignRepositoryMock,
            $getCampaignRepositoryMethodRef->invoke($baseControllerMock)
        );
    }

    /**
     * @return MockInterface&LegacyMockInterface&Campaign
     */
    protected function createCampaignMock()
    {
        return Mockery::mock(Campaign::class);
    }

    /**
     * @covers ::findCampaigns
     * @test
     * @testdox findCampaignsはエンティティTheaterを引数に、エンティティCampaignのリストを取得できる
     */
    public function testFindCampaigns(): void
    {
        $theaterId = 1;

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->once()
            ->with()
            ->andReturn($theaterId);

        $result = [$this->createCampaignMock()];

        $campaignRepositoryMock = $this->createCampaignRepositoryMock();
        $campaignRepositoryMock
            ->shouldReceive('findByTheater')
            ->once()
            ->with($theaterId)
            ->andReturn($result);

        $baseControllerMock = $this->createBaseControllerMock($this->createContainer());
        $baseControllerMock->shouldAllowMockingProtectedMethods();

        $baseControllerMock
            ->shouldReceive('getCampaignRepository')
            ->once()
            ->with()
            ->andReturn($campaignRepositoryMock);

        $baseControllerRef = $this->createBaseControllerReflection();

        $findCampaignsMethodRef = $baseControllerRef->getMethod('findCampaigns');
        $findCampaignsMethodRef->setAccessible(true);

        $this->assertEquals(
            $result,
            $findCampaignsMethodRef->invoke($baseControllerMock, $theaterMock)
        );
    }

    /**
     * @return MockInterface&LegacyMockInterface&NewsRepository
     */
    protected function createNewsRepositoryMock()
    {
        return Mockery::mock(NewsRepository::class);
    }

    /**
     * @covers ::getNewsRepository
     * @test
     * @testdox getNewsRepositoryはエンティティNewsのRepositoryを取得できる
     */
    public function testGetNewsRepository(): void
    {
        $newsRepositoryMock = $this->createNewsRepositoryMock();

        $container = $this->createContainer();

        $container['em']
            ->shouldReceive('getRepository')
            ->once()
            ->with(News::class)
            ->andReturn($newsRepositoryMock);

        $baseControllerMock = $this->createBaseControllerMock($container);

        $baseControllerRef = $this->createBaseControllerReflection();

        $getNewsRepositoryMethodRef = $baseControllerRef->getMethod('getNewsRepository');
        $getNewsRepositoryMethodRef->setAccessible(true);

        $this->assertEquals(
            $newsRepositoryMock,
            $getNewsRepositoryMethodRef->invoke($baseControllerMock)
        );
    }

    /**
     * @return MockInterface&LegacyMockInterface&News
     */
    protected function createNewsMock()
    {
        return Mockery::mock(News::class);
    }

    /**
     * @return array<string,array{?int[],?int}>
     */
    public function findNewsListDataProvider(): array
    {
        return [
            'theater only' => [null, null],
            'with categories' => [[1, 3], null],
            'with limit' => [[], 3],
            'with categories and limit' => [[2, 3, 5], 8],
        ];
    }

    /**
     * @covers ::findNewsList
     * @dataProvider findNewsListDataProvider
     * @test
     * @testdox findNewsListはエンティティTheaterを引数に、エンティティNewsのリストを取得できる
     *
     * @param ?int[] $categories
     */
    public function testFindNewsList(?array $categories, ?int $limit): void
    {
        $theaterId = 1;

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->once()
            ->with()
            ->andReturn($theaterId);

        $findByTheaterArgs   = [];
        $findByTheaterArgs[] = $theaterId;
        $findByTheaterArgs[] = is_null($categories) ? [] : $categories;
        $findByTheaterArgs[] = is_null($limit) ? null : $limit;

        $result = [$this->createNewsMock()];

        $newsRepositoryMock = $this->createNewsRepositoryMock();
        $newsRepositoryMock
            ->shouldReceive('findByTheater')
            ->once()
            ->withArgs($findByTheaterArgs)
            ->andReturn($result);

        $baseControllerMock = $this->createBaseControllerMock($this->createContainer());
        $baseControllerMock->shouldAllowMockingProtectedMethods();

        $baseControllerMock
            ->shouldReceive('getNewsRepository')
            ->once()
            ->with()
            ->andReturn($newsRepositoryMock);

        $baseControllerRef = $this->createBaseControllerReflection();

        $findNewsListMethodRef = $baseControllerRef->getMethod('findNewsList');
        $findNewsListMethodRef->setAccessible(true);

        $args   = [];
        $args[] = $theaterMock;

        if (! is_null($categories)) {
            $args[] = $categories;
        } elseif (! is_null($limit)) {
            $args[] = [];
        }

        if (! is_null($limit)) {
            $args[] = $limit;
        }

        $this->assertEquals(
            $result,
            $findNewsListMethodRef->invokeArgs($baseControllerMock, $args)
        );
    }

    /**
     * @covers ::preExecute
     * @test
     * @testdox preExecuteは引数nameにマッチする劇場が存在する場合、プロパティtheaterにデータをセットする
     */
    public function testPreExecuteCaseTheaterExists(): void
    {
        $name = 'match';
        $args = ['name' => $name];

        $baseControllerMock = $this->createBaseControllerMock($this->createContainer());
        $baseControllerMock->shouldAllowMockingProtectedMethods();

        $theaterMock = $this->createTheaterMock();
        $baseControllerMock
            ->shouldReceive('findOneTheaterByName')
            ->once()
            ->with($name)
            ->andReturn($theaterMock);

        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();

        $baseControllerRef = $this->createBaseControllerReflection();

        $preExecuteMethodRef = $baseControllerRef->getMethod('preExecute');
        $preExecuteMethodRef->setAccessible(true);

        $preExecuteMethodRef->invoke($baseControllerMock, $requestMock, $responseMock, $args);

        $theaterPropertyRef = $baseControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);

        $this->assertEquals($theaterMock, $theaterPropertyRef->getValue($baseControllerMock));
    }

    /**
     * @covers ::preExecute
     * @test
     * @testdox preExecuteは引数nameにマッチする劇場が存在しない場合、例外NotFoundExceptionをthrowする
     */
    public function testPreExecuteCaseTheaterNotExists(): void
    {
        $name = 'unmatch';
        $args = ['name' => $name];

        $baseControllerMock = $this->createBaseControllerMock($this->createContainer());
        $baseControllerMock->shouldAllowMockingProtectedMethods();

        $baseControllerMock
            ->shouldReceive('findOneTheaterByName')
            ->once()
            ->with($name)
            ->andReturn(null);

        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();

        $baseControllerRef = $this->createBaseControllerReflection();

        $preExecuteMethodRef = $baseControllerRef->getMethod('preExecute');
        $preExecuteMethodRef->setAccessible(true);

        $this->expectException(NotFoundException::class);
        $preExecuteMethodRef->invoke($baseControllerMock, $requestMock, $responseMock, $args);
    }

    /**
     * @covers ::postExecute
     * @doesNotPerformAssertions
     * @test
     */
    public function testPostExecute(): void
    {
        // todo
    }
}
