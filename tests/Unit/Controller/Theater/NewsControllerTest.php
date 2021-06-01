<?php

declare(strict_types=1);

namespace Tests\Unit\Controller\Theater;

use App\Controller\Theater\NewsController;
use App\ORM\Entity\News;
use App\ORM\Repository\NewsRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use ReflectionClass;
use Slim\Container;
use Slim\Exception\NotFoundException;

/**
 * @coversDefaultClass \App\Controller\Theater\NewsController
 */
final class NewsControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&NewsController
     */
    protected function createNewsControllerMock(Container $container)
    {
        return Mockery::mock(NewsController::class, [$container]);
    }

    protected function createNewsControllerReflection(): ReflectionClass
    {
        return new ReflectionClass(NewsController::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&NewsRepository
     */
    protected function createNewsRepositoryMock()
    {
        return Mockery::mock(NewsRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&News
     */
    protected function createNewsMock()
    {
        return Mockery::mock(News::class);
    }

    /**
     * @covers ::findOneNewsById
     * @test
     * @testdox findOneNewsByIdは引数$idを受け取り、存在する場合はエンティティNewsのオブジェクトを返す
     */
    public function testFindOneNewsByIdCaseExists(): void
    {
        $id       = 1;
        $newsMock = $this->createNewsMock();

        $newsRepositoryMock = $this->createNewsRepositoryMock();
        $newsRepositoryMock
            ->shouldReceive('findOneById')
            ->once()
            ->with($id)
            ->andReturn($newsMock);

        $newsControllerMock = $this->createNewsControllerMock($this->createContainer());
        $newsControllerMock->shouldAllowMockingProtectedMethods();
        $newsControllerMock
            ->shouldReceive('getNewsRepository')
            ->once()
            ->with()
            ->andReturn($newsRepositoryMock);

        $newsControllerRef = $this->createNewsControllerReflection();

        $findOneNewsByIdMethodRef = $newsControllerRef->getMethod('findOneNewsById');
        $findOneNewsByIdMethodRef->setAccessible(true);

        $this->assertEquals(
            $newsMock,
            $findOneNewsByIdMethodRef->invoke($newsControllerMock, $id)
        );
    }

    /**
     * @covers ::findOneNewsById
     * @test
     * @testdox findOneNewsByIdは引数$idを受け取り、存在しない場合はnullを返す
     */
    public function testFindOneNewsByIdCaseNotExists(): void
    {
        $id = 99;

        $newsRepositoryMock = $this->createNewsRepositoryMock();
        $newsRepositoryMock
            ->shouldReceive('findOneById')
            ->once()
            ->with($id)
            ->andReturn(null);

        $newsControllerMock = $this->createNewsControllerMock($this->createContainer());
        $newsControllerMock->shouldAllowMockingProtectedMethods();
        $newsControllerMock
            ->shouldReceive('getNewsRepository')
            ->once()
            ->with()
            ->andReturn($newsRepositoryMock);

        $newsControllerRef = $this->createNewsControllerReflection();

        $findOneNewsByIdMethodRef = $newsControllerRef->getMethod('findOneNewsById');
        $findOneNewsByIdMethodRef->setAccessible(true);

        $this->assertNull($findOneNewsByIdMethodRef->invoke($newsControllerMock, $id));
    }

    /**
     * @covers ::executeIndex
     * @test
     * @testdox executeIndexはテンプレートtheater/news/index.html.twigを描画する
     */
    public function testExecuteIndex(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $theaterMock = $this->createTheaterMock();

        $newsControllerMock = $this->createNewsControllerMock($this->createContainer());
        $newsControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $newsControllerRef = $this->createNewsControllerReflection();

        $theaterPropertyRef = $newsControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($newsControllerMock, $theaterMock);

        $newsList = [];
        $newsControllerMock
            ->shouldReceive('findNewsList')
            ->once()
            ->with($theaterMock)
            ->andReturn($newsList);

        $campaigns = [];
        $newsControllerMock
            ->shouldReceive('findCampaigns')
            ->once()
            ->with($theaterMock)
            ->andReturn($campaigns);

        $data = [
            'theater' => $theaterMock,
            'newsList' => $newsList,
            'campaigns' => $campaigns,
        ];
        $newsControllerMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/news/index.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $newsControllerMock->executeIndex($requestMock, $responseMock, $args)
        );
    }

    /**
     * @covers ::executeShow
     * @test
     * @testdox executeShowは引数idのNewsが存在する場合、テンプレートtheater/news/show.html.twigを描画する
     */
    public function testExecuteShowCaseNewsExists(): void
    {
        $id = '1';

        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['id' => $id];

        $theaterMock = $this->createTheaterMock();

        $newsControllerMock = $this->createNewsControllerMock($this->createContainer());
        $newsControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $newsControllerRef = $this->createNewsControllerReflection();

        $theaterPropertyRef = $newsControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($newsControllerMock, $theaterMock);

        $newsMock = $this->createNewsMock();
        $newsControllerMock
            ->shouldReceive('findOneNewsById')
            ->once()
            ->with((int) $id)
            ->andReturn($newsMock);

        $data = [
            'theater' => $theaterMock,
            'news' => $newsMock,
        ];
        $newsControllerMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/news/show.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $newsControllerMock->executeShow($requestMock, $responseMock, $args)
        );
    }

    /**
     * @covers ::executeShow
     * @test
     * @testdox executeShowは引数idのNewsが存在しない場合、例外NotFoundExceptionをthrowする
     */
    public function testExecuteShowCaseNewsNotExists(): void
    {
        $id = '99';

        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = ['id' => $id];

        $theaterMock = $this->createTheaterMock();

        $newsControllerMock = $this->createNewsControllerMock($this->createContainer());
        $newsControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $newsControllerRef = $this->createNewsControllerReflection();

        $theaterPropertyRef = $newsControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($newsControllerMock, $theaterMock);

        $newsControllerMock
            ->shouldReceive('findOneNewsById')
            ->once()
            ->with((int) $id)
            ->andReturn(null);

        $this->expectException(NotFoundException::class);
        $newsControllerMock->executeShow($requestMock, $responseMock, $args);
    }
}
