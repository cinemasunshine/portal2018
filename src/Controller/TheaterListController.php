<?php
/**
 * TheaterListController.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

use Cinemasunshine\Portal\ORM\Entity;

/**
 * Theater List controller
 */
class TheaterListController extends GeneralController
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
        $this->data->set('areaToTheaters', $this->getTheaters());
    }
    
    /**
     * return theaters
     *
     * @return array
     */
    protected function getTheaters()
    {
        $theaters = parent::getTheaters();
        
        $areaToTheaters = [];
        
        foreach ($theaters as $theater) {
            /** @var Entity\Theater $theater */
            $area = $theater->getArea();
            
            if (!isset($areaToTheaters[$area])) {
                $areaToTheaters[$area] = [];
            }
            
            $areaToTheaters[$area][] = $theater;
        }
        
        return $areaToTheaters;
    }
}
