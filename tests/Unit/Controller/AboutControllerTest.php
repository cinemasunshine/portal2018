<?php

declare(strict_types=1);

namespace Tests\Unit\Controller;

use App\Controller\AboutController;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

final class AboutControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&AboutController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AboutController::class);
    }

    /**
     * @test
     */
    public function testExecuteCompany(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'about/company.html.twig')
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeCompany($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteMailMagazine(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'about/mail_magazine.html.twig')
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeMailMagazine($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteMvtk(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'about/mvtk.html.twig')
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeMvtk($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteOnlineTicket(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'about/online_ticket.html.twig')
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeOnlineTicket($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecutePrivacy(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'about/privacy.html.twig')
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executePrivacy($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteQuestion(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'about/question.html.twig')
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeQuestion($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteSitemap(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $theaters = [];
        $targetMock
            ->shouldReceive('findTheaters')
            ->once()
            ->with()
            ->andReturn($theaters);

        $data = ['theaters' => $theaters];
        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'about/sitemap.html.twig', $data)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeSitemap($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteSpecialTicket(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'about/special_ticket.html.twig')
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeSpecialTicket($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteSpecificQuotient(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'about/specific_quotient.html.twig')
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeSpecificQuotient($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     */
    public function testExecuteTermsOfService(): void
    {
        $requestMock  = $this->createRequestMock();
        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('render')
            ->once()
            ->with($responseMock, 'about/terms_of_service.html.twig')
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeTermsOfService($requestMock, $responseMock, $args)
        );
    }
}
