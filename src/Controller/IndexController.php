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
        $this->data->set('mainBanners', $this->getMainBanners());
        
        $this->data->set('areaToTheaters', $this->getTheaters());
        
        $this->data->set('titleRanking', $this->getTitleRanking());
        
        $this->data->set('newsList', $this->getNewsList());
    }
    
    /**
     * return main_banners
     *
     * @return Entity\MainBanner[]
     */
    protected function getMainBanners()
    {
        return $this->em
            ->getRepository(Entity\MainBanner::class)
            ->findByPageId(self::PAGE_ID);
    }
    
    /**
     * return theaters
     *
     * @return array
     */
    protected function getTheaters()
    {
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
        
        return $areaToTheaters;
    }
    
    /**
     * return title_raning
     *
     * @return Entity\TitleRanking
     */
    protected function getTitleRanking()
    {
        return $this->em->find(Entity\TitleRanking::class, 1);
    }
    
    /**
     * return news list
     *
     * @return Entity\News[]
     */
    protected function getNewsList()
    {
        return $this->em
            ->getRepository(Entity\News::class)
            ->findByPage(self::PAGE_ID, Entity\News::CATEGORY_NEWS, 8);
    }
}