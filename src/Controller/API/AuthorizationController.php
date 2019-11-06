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
    const USER_TYPE_VISITOR = 'visitor';
    const USER_TYPE_MEMBER = 'member';

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
        $userType = $request->getParam('user_type');

        if ($userType === 'visitor') {
            $data = $this->executeVisitorToken();

            $this->data->set('meta', $meta);
            $this->data->set('data', $data);
            return;
        } elseif ($userType === 'member') {
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

            $this->data->set('meta', $meta);
            $this->data->set('data', $data);
            return;
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
            $settings['cliennt_credentials_client_secret']);

        $token = $clientCredentialsGrant->requestToken();

        return [
            'access_token' => $token->getAccessToken(),
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

        return [
            'access_token' => $token->getAccessToken(),
        ];
    }
}
