<?php
/**
 * IndexController.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

use Cinemasunshine\Portal\ORM\Entity;

/**
 * Index controller
 */
class IndexController extends BaseController
{
    const PAGE_ID = 1;
    
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
        /** @var MainBanner[] $mainBanners */
        $mainBanners = $this->em
            ->getRepository(Entity\MainBanner::class)
            ->findByPageId(self::PAGE_ID);
        
        $this->data->set('mainBanners', $mainBanners);
        
        $theaters = $this->em
            ->getRepository(Entity\Theater::class)
            ->findByActive();
        
        $areaToTheaters = [];
        
        foreach ($theaters as $theater) {
            /** @var Entity\Theater $theater */
            $area = $theater->getArea();
            
            if (!isset($areaToTheaters[$area])) {
                $areaToTheaters[$area] = [];
            }
            
            $areaToTheaters[$area][] = $theater;
        }
        
        $this->data->set('areaToTheaters', $areaToTheaters);
    }
}