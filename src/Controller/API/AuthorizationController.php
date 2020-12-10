<?php

/**
 * AuthorizationController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace App\Controller\API;

use App\Authorization\Grant\ClientCredentials;
use App\Exception\NotAuthenticatedException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

/**
 * Authorization controller
 */
class AuthorizationController extends BaseController
{
    public const USER_TYPE_VISITOR = 'visitor';
    public const USER_TYPE_MEMBER  = 'member';

    /**
     * token action
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function executeToken(Request $request, Response $response, array $args)
    {
        $meta     = ['name' => 'Authorization Token API'];
        $data     = [];
        $userType = $request->getParam('user_type');

        if ($userType === self::USER_TYPE_VISITOR) {
            $meta['type'] = self::USER_TYPE_VISITOR;
            $data         = $this->executeVisitorToken();
        } elseif ($userType === self::USER_TYPE_MEMBER) {
            $meta['type'] = self::USER_TYPE_MEMBER;

            try {
                $data = $this->executeMemberToken();
            } catch (NotAuthenticatedException $e) {
                $error = [
                    'title' => 'Bad Request',
                    'detail' => 'Not authenticated.',
                ];

                return $response->withJson([
                    'meta' => $meta,
                    'error' => $error,
                ], StatusCode::HTTP_BAD_REQUEST);
            }
        } else {
            // invalid user_type

            $error = [
                'title' => 'Bad Request',
                'detail' => 'Invalid parameter.',
            ];

            return $response->withJson([
                'meta' => $meta,
                'error' => $error,
            ], StatusCode::HTTP_BAD_REQUEST);
        }

        return $response->withJson([
            'meta' => $meta,
            'data' => $data,
        ]);
    }

    /**
     * execute visitor token
     *
     * @return array
     */
    protected function executeVisitorToken(): array
    {
        $settings = $this->settings['mp_service'];

        $clientCredentialsGrant = new ClientCredentials(
            $settings['cliennt_credentials_host'],
            $settings['cliennt_credentials_client_id'],
            $settings['cliennt_credentials_client_secret']
        );

        $token = $clientCredentialsGrant->requestToken();

        return [
            'access_token' => $token->getAccessToken(),
            'expires_in' => $token->getExpiresIn(),
        ];
    }

    /**
     * execute member token
     *
     * @return array
     */
    protected function executeMemberToken(): array
    {
        if (! $this->um->isAuthenticated()) {
            throw new NotAuthenticatedException('Not authenticated');
        }

        $token = $this->um->getAuthorizationToken();

        $settings = $this->settings['mp_service'];

        $expirationBuffer = $settings['authorization_token_expiration_buffer'];
        $expires          = $token->getExpires() - $expirationBuffer;
        $now              = time();

        $this->logger->debug(sprintf('Choeck token expires. (%s > %s)', $now, $expires));

        if ($now > $expires) {
            // トークン期限切れ

            $this->logger->debug('Refreshing a Token');

            $token = $this->am->refreshToken($token->getRefreshToken());

            // ユーザのtokenを更新
            $this->um->setAuthorizationToken($token);
        }

        return [
            'access_token' => $token->getAccessToken(),
            'expires_in' => $token->getExpiresIn(),
        ];
    }
}
