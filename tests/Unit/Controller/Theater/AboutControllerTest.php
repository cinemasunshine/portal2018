<?php

declare(strict_types=1);

namespace Tests\Unit\Controller\Theater;

use App\Controller\Theater\AboutController;
use App\ORM\Entity\News;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use ReflectionClass;
use Slim\Container;

/**
 * @coversDefaultClass \App\Controller\Theater\AboutController
 */
final class AboutControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&AboutController
     */
    protected function createAboutControllerMock(Container $container)
    {
        return Mockery::mock(AboutController::class, [$container]);
    }

    protected function createAboutControllerReflection(): ReflectionClass
    {
        return new ReflectionClass(AboutController::class);
    }

    /**
     * @covers ::findInfoNewsList
     * @test
     * @testdox findInfoNewsListはエンティティNewsのリストを取得できる
     */
    public function testFindInfoNewsList(): void
    {
        $theaterMock = $this->createTheaterMock();

        $aboutControllerMock = $this->createAboutControllerMock($this->createContainer());
        $aboutControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $result = [Mockery::mock(News::class)];
        $aboutControllerMock
            ->shouldReceive('findNewsList')
            ->once()
            ->with($theaterMock, [News::CATEGORY_INFO], Mockery::type('int'))
            ->andReturn($result);

        $aboutControllerRef = $this->createAboutControllerReflection();

        $findInfoNewsListMethodRef = $aboutControllerRef->getMethod('findInfoNewsList');
        $findInfoNewsListMethodRef->setAccessible(true);

        $this->assertEquals(
            $result,
            $findInfoNewsListMethodRef->invoke($aboutControllerMock, $theaterMock)
        );
    }

    /**
     * @covers ::executeAccess
     * @test
     * @testdox executeAccessはテンプレートtheater/access.html.twigを描画する
     */
    public function testExecuteAccess(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $theaterMock = $this->createTheaterMock();

        $aboutControllerMock = $this->createAboutControllerMock($this->createContainer());
        $aboutControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $aboutControllerRef = $this->createAboutControllerReflection();

        $theaterPropertyRef = $aboutControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($aboutControllerMock, $theaterMock);

        $infoNewsList = [];
        $aboutControllerMock
            ->shouldReceive('findInfoNewsList')
            ->once()
            ->with($theaterMock)
            ->andReturn($infoNewsList);

        $data = [
            'theater' => $theaterMock,
            'infoNewsList' => $infoNewsList,
        ];
        $aboutControllerMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/access.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $aboutControllerMock->executeAccess($requestMock, $responseMock, $args)
        );
    }

    /**
     * @covers ::executeAdmission
     * @test
     * @testdox executeAdmissionはテンプレートtheater/admission.html.twigを描画する
     */
    public function testExecuteAdmission(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $theaterMock = $this->createTheaterMock();

        $aboutControllerMock = $this->createAboutControllerMock($this->createContainer());
        $aboutControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $aboutControllerRef = $this->createAboutControllerReflection();

        $theaterPropertyRef = $aboutControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($aboutControllerMock, $theaterMock);

        $campaigns = [];
        $aboutControllerMock
            ->shouldReceive('findCampaigns')
            ->once()
            ->with($theaterMock)
            ->andReturn($campaigns);

        $data = [
            'theater' => $theaterMock,
            'campaigns' => $campaigns,
        ];
        $aboutControllerMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/admission.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $aboutControllerMock->executeAdmission($requestMock, $responseMock, $args)
        );
    }

    /**
     * @covers ::executeConcession
     * @test
     * @testdox executeConcessionはテンプレートtheater/concession.html.twigを描画する
     */
    public function testExecuteConcession(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $aboutControllerMock = $this->createAboutControllerMock($this->createContainer());
        $aboutControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $aboutControllerRef = $this->createAboutControllerReflection();

        $theaterMock = $this->createTheaterMock();

        $theaterPropertyRef = $aboutControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($aboutControllerMock, $theaterMock);

        $campaigns = [];
        $aboutControllerMock
            ->shouldReceive('findCampaigns')
            ->once()
            ->with($theaterMock)
            ->andReturn($campaigns);

        $infoNewsList = [];
        $aboutControllerMock
            ->shouldReceive('findInfoNewsList')
            ->once()
            ->with($theaterMock)
            ->andReturn($infoNewsList);

        $data = [
            'theater' => $theaterMock,
            'campaigns' => $campaigns,
            'infoNewsList' => $infoNewsList,
        ];
        $aboutControllerMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/concession.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $aboutControllerMock->executeConcession($requestMock, $responseMock, $args)
        );
    }

    /**
     * @covers ::executeFloorGuide
     * @test
     * @testdox executeFloorGuideはテンプレートtheater/floor_guide.html.twigを描画する
     */
    public function testExecuteFloorGuide(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $aboutControllerMock = $this->createAboutControllerMock($this->createContainer());
        $aboutControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $aboutControllerRef = $this->createAboutControllerReflection();

        $theaterMock = $this->createTheaterMock();

        $theaterPropertyRef = $aboutControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($aboutControllerMock, $theaterMock);

        $infoNewsList = [];
        $aboutControllerMock
            ->shouldReceive('findInfoNewsList')
            ->once()
            ->with($theaterMock)
            ->andReturn($infoNewsList);

        $data = [
            'theater' => $theaterMock,
            'infoNewsList' => $infoNewsList,
        ];
        $aboutControllerMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/floor_guide.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $aboutControllerMock->executeFloorGuide($requestMock, $responseMock, $args)
        );
    }
}
