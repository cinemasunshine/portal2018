<?php

declare(strict_types=1);

namespace Tests\Unit\Controller\API;

use App\Controller\API\AuthorizationController;
use App\Exception\NotAuthenticatedException;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Slim\Http\StatusCode;
use Tests\Unit\Controller\BaseTestCase;

final class AuthorizationControllerTest extends BaseTestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&AuthorizationController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AuthorizationController::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteTokenTypeVisitor()
    {
        $userType = AuthorizationController::USER_TYPE_VISITOR;

        $requestMock = $this->createRequestMock();
        $requestMock
            ->shouldReceive('getParam')
            ->once()
            ->with('user_type')
            ->andReturn($userType);

        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $data = [
            'access_token' => 'visitor-token',
            'expires_in' => 12345,
        ];
        $targetMock
            ->shouldReceive('executeVisitorToken')
            ->once()
            ->with()
            ->andReturn($data);

        $meta     = [
            'name' => 'Authorization Token API',
            'type' => $userType,
        ];
        $jsonData = [
            'meta' => $meta,
            'data' => $data,
        ];
        $responseMock
            ->shouldReceive('withJson')
            ->once()
            ->with($jsonData)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeToken($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteTokenTypeMember()
    {
        $userType = AuthorizationController::USER_TYPE_MEMBER;

        $requestMock = $this->createRequestMock();
        $requestMock
            ->shouldReceive('getParam')
            ->once()
            ->with('user_type')
            ->andReturn($userType);

        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $data = [
            'access_token' => 'member-token',
            'expires_in' => 12345,
        ];
        $targetMock
            ->shouldReceive('executeMemberToken')
            ->once()
            ->with()
            ->andReturn($data);

        $meta     = [
            'name' => 'Authorization Token API',
            'type' => $userType,
        ];
        $jsonData = [
            'meta' => $meta,
            'data' => $data,
        ];
        $responseMock
            ->shouldReceive('withJson')
            ->once()
            ->with($jsonData)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeToken($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteTokenTypeMemberNotAuthenticated()
    {
        $userType = AuthorizationController::USER_TYPE_MEMBER;

        $requestMock = $this->createRequestMock();
        $requestMock
            ->shouldReceive('getParam')
            ->once()
            ->with('user_type')
            ->andReturn($userType);

        $responseMock = $this->createResponseMock();
        $args         = [];

        $targetMock = $this->createTargetMock();
        $targetMock
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $targetMock
            ->shouldReceive('executeMemberToken')
            ->once()
            ->with()
            ->andThrow(new NotAuthenticatedException());

        $meta     = [
            'name' => 'Authorization Token API',
            'type' => $userType,
        ];
        $error    = [
            'title' => 'Bad Request',
            'detail' => 'Not authenticated.',
        ];
        $jsonData = [
            'meta' => $meta,
            'error' => $error,
        ];
        $responseMock
            ->shouldReceive('withJson')
            ->once()
            ->with($jsonData, StatusCode::HTTP_BAD_REQUEST)
            ->andReturn($responseMock);

        $this->assertEquals(
            $responseMock,
            $targetMock->executeToken($requestMock, $responseMock, $args)
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function testExecuteTokenInvalidType()
    {
        $userType = 'invalid';

        $requestMock = $this->createRequestMock();
        $requestMock
            ->shouldReceive('getParam')
            ->once()
            ->with('user_type')
            ->andReturn($userType);

        $responseMock = $this->createResponseMock();

        $meta     = ['name' => 'Authorization Token API'];
        $error    = [
            'title' => 'Bad Request',
            'detail' => 'Invalid parameter.',
        ];
        $jsonData = [
            'meta' => $meta,
            'error' => $error,
        ];
        $responseMock
            ->shouldReceive('withJson')
            ->once()
            ->with($jsonData, StatusCode::HTTP_BAD_REQUEST)
            ->andReturn($responseMock);

        $args = [];

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $this->assertEquals(
            $responseMock,
            $targetMock->executeToken($requestMock, $responseMock, $args)
        );
    }
}
