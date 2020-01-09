<?php
/**
 * OyakoCinemaController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

use Slim\Exception\NotFoundException;

use Cinemasunshine\Portal\ORM\Entity;

/**
 * OyakoCinema controller
 */
class OyakoCinemaController extends GeneralController
{
    /**
     * index action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeIndex($request, $response, $args)
    {
        $this->data->set('oyakoCinemaTitles', $this->getList());
    }

    /**
     * return list
     *
     * @return Entity\OyakoCinemaTitle[]
     */
    protected function getList()
    {
        return $this->em
            ->getRepository(Entity\OyakoCinemaTitle::class)
            ->findByActive();
    }
}
