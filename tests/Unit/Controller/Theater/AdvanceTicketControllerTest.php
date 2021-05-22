<?php

declare(strict_types=1);

namespace Tests\Unit\Controller\Theater;

use App\Controller\Theater\AdvanceTicketController;
use App\ORM\Entity\AdvanceTicket;
use App\ORM\Entity\News;
use App\ORM\Repository\AdvanceTicketRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use ReflectionClass;
use Slim\Container;

/**
 * @coversDefaultClass \App\Controller\Theater\AdvanceTicketController
 */
final class AdvanceTicketControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&AdvanceTicketController
     */
    protected function createAdvanceTicketControllerMock(Container $container)
    {
        return Mockery::mock(AdvanceTicketController::class, [$container]);
    }

    protected function createAdvanceTicketControllerReflection(): ReflectionClass
    {
        return new ReflectionClass(AdvanceTicketController::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&AdvanceTicketRepository
     */
    protected function createAdvanceTicketRepositoryMock()
    {
        return Mockery::mock(AdvanceTicketRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&AdvanceTicket
     */
    protected function createAcvanceTicketMock()
    {
        return Mockery::mock(AdvanceTicket::class);
    }

    /**
     * @covers ::getAdvanceTicketRepository
     * @test
     * @testdox getAdvanceTicketRepositoryはエンティティAdvanceTicketのRepositoryを取得できる
     */
    public function testGetAdvanceTicketRepository(): void
    {
        $advanceTicketRepositoryMock = $this->createAdvanceTicketRepositoryMock();

        $container = $this->createContainer();

        $container['em']
            ->shouldReceive('getRepository')
            ->once()
            ->with(AdvanceTicket::class)
            ->andReturn($advanceTicketRepositoryMock);

        $advanceTicketControllerMock = $this->createAdvanceTicketControllerMock($container);

        $advanceTicketControllerRef = $this->createAdvanceTicketControllerReflection();

        $getAdvanceTicketRepositoryMethodRef = $advanceTicketControllerRef->getMethod('getAdvanceTicketRepository');
        $getAdvanceTicketRepositoryMethodRef->setAccessible(true);

        $this->assertEquals(
            $advanceTicketRepositoryMock,
            $getAdvanceTicketRepositoryMethodRef->invoke($advanceTicketControllerMock)
        );
    }

    /**
     * @covers ::findAdvanceTickets
     * @test
     * @testdox findAdvanceTicketsはエンティティAdvanceTicketのリストを取得できる
     */
    public function testFindAdvanceTickets(): void
    {
        $theaterId = 1;

        $theaterMock = $this->createTheaterMock();
        $theaterMock
            ->shouldReceive('getId')
            ->once()
            ->with()
            ->andReturn($theaterId);

        $result = [$this->createAcvanceTicketMock()];

        $advanceTicketRepositoryMock = $this->createAdvanceTicketRepositoryMock();
        $advanceTicketRepositoryMock
            ->shouldReceive('findByTheater')
            ->once()
            ->with($theaterId)
            ->andReturn($result);

        $advanceTicketControllerMock = $this->createAdvanceTicketControllerMock($this->createContainer());
        $advanceTicketControllerMock->shouldAllowMockingProtectedMethods();

        $advanceTicketControllerMock
            ->shouldReceive('getAdvanceTicketRepository')
            ->once()
            ->with()
            ->andReturn($advanceTicketRepositoryMock);

        $advanceTicketControllerRef = $this->createAdvanceTicketControllerReflection();

        $findAdvanceTicketsMethodRef = $advanceTicketControllerRef->getMethod('findAdvanceTickets');
        $findAdvanceTicketsMethodRef->setAccessible(true);

        $this->assertEquals(
            $result,
            $findAdvanceTicketsMethodRef->invoke($advanceTicketControllerMock, $theaterMock)
        );
    }

    /**
     * @covers ::executeIndex
     * @test
     * @testdox executeIndexはテンプレートtheater/advance_ticket.html.twigを描画する
     */
    public function testExecuteIndex(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $theaterMock = $this->createTheaterMock();

        $advanceTicketControllerMock = $this->createAdvanceTicketControllerMock($this->createContainer());
        $advanceTicketControllerMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $advanceTicketControllerRef = $this->createAdvanceTicketControllerReflection();

        $theaterPropertyRef = $advanceTicketControllerRef->getProperty('theater');
        $theaterPropertyRef->setAccessible(true);
        $theaterPropertyRef->setValue($advanceTicketControllerMock, $theaterMock);

        $advanceTickets = [$this->createAcvanceTicketMock()];
        $advanceTicketControllerMock
            ->shouldReceive('findAdvanceTickets')
            ->once()
            ->with($theaterMock)
            ->andReturn($advanceTickets);

        $campaigns = [];
        $advanceTicketControllerMock
            ->shouldReceive('findCampaigns')
            ->once()
            ->with($theaterMock)
            ->andReturn($campaigns);

        $infoNewsList = [];
        $advanceTicketControllerMock
            ->shouldReceive('findNewsList')
            ->once()
            ->with($theaterMock, [News::CATEGORY_INFO], 8)
            ->andReturn($infoNewsList);

        $data = [
            'theater' => $theaterMock,
            'advanceTickets' => $advanceTickets,
            'campaigns' => $campaigns,
            'infoNewsList' => $infoNewsList,
        ];
        $advanceTicketControllerMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'theater/advance_ticket.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $advanceTicketControllerMock->executeIndex($requestMock, $responseMock, $args)
        );
    }
}
