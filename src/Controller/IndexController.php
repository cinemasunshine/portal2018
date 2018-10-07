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
class IndexController extends GeneralController
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
        $this->data->set('page', $this->getPage(self::PAGE_ID));
        
        $this->data->set('mainBanners', $this->getMainBanners());
        
        $this->data->set('areaToTheaters', $this->getTheaters());
        
        $this->data->set('titleRanking', $this->getTitleRanking());
        
        $this->data->set('newsList', $this->getNewsList(Entity\News::CATEGORY_NEWS));
        
        $this->data->set('imaxNewsList', $this->getNewsList(Entity\News::CATEGORY_IMAX));
        
        // twigは数値から始まる変数が利用できない
        $this->data->set('fourdxNewsList', $this->getNewsList(Entity\News::CATEGORY_4DX));
        
        $this->data->set('infoNewsList', $this->getNewsList(Entity\News::CATEGORY_INFO));
        
        $this->data->set('campaigns', $this->getCampaigns(self::PAGE_ID));
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
     * @param int $category
     * @return Entity\News[]
     */
    protected function getNewsList(int $category)
    {
        return $this->em
            ->getRepository(Entity\News::class)
            ->findByPage(self::PAGE_ID, $category, 8);
    }
}