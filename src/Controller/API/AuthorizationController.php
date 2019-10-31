<?php
/**
 * AuthorizationController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller\API;

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
            // request visitor token
            return;
        } elseif ($userType === 'member') {
            // request member token
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
}
