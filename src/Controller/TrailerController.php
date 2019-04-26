<?php
/**
 * TrailerController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

use Slim\Exception\NotFoundException;

use Cinemasunshine\Portal\ORM\Entity;

/**
 * Trailer controller
 */
class TrailerController extends BaseController
{
    /**
     * show action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeShow($request, $response, $args)
    {
        $trailers = [];
        
        if ($pageId = $request->getParam('p')) {
            $trailers = $this->em
                ->getRepository(Entity\Trailer::class)
                ->findByPage((int) $pageId);
        } elseif ($theaterId = $request->getParam('t')) {
            $trailers = $this->em
                ->getRepository(Entity\Trailer::class)
                ->findByTheater((int) $theaterId);
        }
        
        if (count($trailers) === 0) {
            throw new NotFoundException($request, $response);
        }
        
        // シャッフルしてランダムに１件取得する
        shuffle($trailers);
        
        /** @var Entity\Trailer $trailer */
        $trailer  = $trailers[0];
        
        $this->data->set('trailer', $trailer);
    }
}
