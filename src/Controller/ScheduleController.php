<?php
/**
 * ScheduleController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

use Slim\Exception\NotFoundException;

use Cinemasunshine\Portal\ORM\Entity;

/**
 * Schedule controller
 */
class ScheduleController extends GeneralController
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
        $schedule = $this->em
            ->getRepository(Entity\Schedule::class)
            ->findOneById($args['schedule']);
        
        if (is_null($schedule)) {
            throw new NotFoundException($request, $response);
        }
        
        /**@var Entity\Schedule $schedule */
        
        $this->data->set('schedule', $schedule);
        
        $this->data->set('theaters', $this->getTheaters());
    }
}
