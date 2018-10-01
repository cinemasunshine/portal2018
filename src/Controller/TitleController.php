<?php
/**
 * TitleController.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

use Slim\Exception\NotFoundException;

use Cinemasunshine\Portal\ORM\Entity;

/**
 * Title controller
 */
class TitleController extends GeneralController
{
    /**
     * list action
     * 
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeList($request, $response, $args)
    {
        $this->data->set('theaters', $this->getTheaters());
        
        $this->data->set('screeningSchedules', $this->getScreeningSchedules());
        
        $this->data->set('soonSchedules', $this->getSoonSchedules());
    }
    
    /**
     * return screening schedules
     *
     * @return Entity\Schedule[]
     */
    protected function getScreeningSchedules()
    {
        return $this->em
            ->getRepository(Entity\Schedule::class)
            ->findScreening();
    }
    
    /**
     * return soon schedules
     *
     * @return Entity\Schedule[]
     */
    protected function getSoonSchedules()
    {
        return $this->em
            ->getRepository(Entity\Schedule::class)
            ->findSoon();
    }
    
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
        $title = $this->em
            ->getRepository(Entity\Title::class)
            ->findOneById($args['id']);
        
        if (is_null($title)) {
            throw new NotFoundException($request, $response);
        }
        
        /**@var Entity\Title $title */
        
        $this->data->set('title', $title);
        
        $this->data->set('theaters', $this->getTheaters());
    }
}
