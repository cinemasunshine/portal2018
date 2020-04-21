<?php

/**
 * AuthorizationController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller\API;

use Cinemasunshine\Portal\Authorization\Grant\ClientCredentials;
use Cinemasunshine\Portal\Exception\NotAuthenticatedException;

/**
 * Authorization controller
 */
class AuthorizationController extends BaseController
{
    public const USER_TYPE_VISITOR = 'visitor';
    public const USER_TYPE_MEMBER = 'member';

    /**
     * token action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeToken($request, $response, $args)
    {
        $meta = [
            'name' => 'Authorization Token API',
        ];
        $data = [];
        $userType = $request->getParam('user_type');

        if ($userType === self::USER_TYPE_VISITOR) {
            $meta['type'] = self::USER_TYPE_VISITOR;
            $data = $this->executeVisitorToken();
        } elseif ($userType === self::USER_TYPE_MEMBER) {
            $meta['type'] = self::USER_TYPE_MEMBER;

            try {
                $data = $this->executeMemberToken();
            } catch (NotAuthenticatedException $e) {
                $this->data->set('meta', $meta);
                $error = [
                    'title' => 'Bad Request',
                    'detail' => 'Not authenticated.'
                ];
                $this->data->set('error', $error);

                return 'badRequest';
            }
        } else {
            // invalid user_type
            $this->data->set('meta', $meta);

            $error = [
                'title' => 'Bad Request',
                'detail' => 'Invalid parameter.'
            ];
            $this->data->set('error', $error);

            return 'badRequest';
        }

        $this->data->set('meta', $meta);
        $this->data->set('data', $data);
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
        if (!$this->um->isAuthenticated()) {
            throw new NotAuthenticatedException('Not authenticated');
        }

        $token = $this->um->getAuthorizationToken();

        $settings = $this->settings['mp_service'];

        $expirationBuffer = $settings['authorization_token_expiration_buffer'];
        $expires = $token->getExpires() - $expirationBuffer;
        $now = time();

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
