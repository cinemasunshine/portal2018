<?php
/**
 * FourdxController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

use Slim\Exception\NotFoundException;

use Cinemasunshine\Portal\ORM\Entity;

/**
 * Fourdx controller
 *
 * 4DX特設サイト
 */
class FourdxController extends SpecialSiteController
{
    const SPECIAL_SITE_ID = 2;
    
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
        
        $this->data->set('trailers', $this->getTrailers());
        
        $this->data->set('newsList', $this->getNewsList(8));
        
        $this->data->set('theaters', $this->get4dxTheaters());
        
        $this->data->set('screeningSchedules', $this->getScreeningSchedules());
        
        $this->data->set('soonSchedules', $this->getSoonSchedules());
        
        $this->data->set('campaigns', $this->getCampaigns());
        
        $this->data->set('infoNewsList', $this->getInfoNewsList(4));
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
            ->findBySpecialSiteId(self::SPECIAL_SITE_ID);
    }
    
    /**
     * return trailers
     *
     * @return Entity\Trailer[]
     */
    protected function getTrailers()
    {
        return $this->em
            ->getRepository(Entity\Trailer::class)
            ->findBySpecialSite(self::SPECIAL_SITE_ID);
    }
    
    /**
     * return news list
     *
     * @param int|null $limt
     * @return Entity\News[]
     */
    protected function getNewsList(?int $limt = null)
    {
        return $this->em
            ->getRepository(Entity\News::class)
            ->findBy4dx();
    }
    
    /**
     * return 4DX theaters
     *
     * @return Entity\Theater[]
     */
    protected function get4dxTheaters()
    {
        return $this->em
            ->getRepository(Entity\Theater::class)
            ->findBySpecialSite(self::SPECIAL_SITE_ID);
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
            ->findScreeningFor4dx();
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
            ->findSoonFor4dx();
    }
    
    /**
     * return campaigns
     *
     * @return Entity\Campaign[]
     */
    protected function getCampaigns()
    {
        return $this->em
            ->getRepository(Entity\Campaign::class)
            ->findBySpecialSite(self::SPECIAL_SITE_ID);
    }
    
    /**
     * return information news list
     *
     * @param int|null $limit
     * @return Entity\News[]
     */
    protected function getInfoNewsList(?int $limit = null)
    {
        return $this->em
            ->getRepository(Entity\News::class)
            ->findBySpecialSite(self::SPECIAL_SITE_ID, Entity\News::CATEGORY_INFO, $limit);
    }
    
    /**
     * about action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeAbout($request, $response, $args)
    {
    }
    
    /**
     * schedule list action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeScheduleList($request, $response, $args)
    {
        $this->data->set('theaters', $this->get4dxTheaters());
        
        $this->data->set('screeningSchedules', $this->getScreeningSchedules());
        
        $this->data->set('soonSchedules', $this->getSoonSchedules());
    }
    
    /**
     * schedule show action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeScheduleShow($request, $response, $args)
    {
        $schedule = $this->em
            ->getRepository(Entity\Schedule::class)
            ->findOneById($args['schedule']);
        
        if (is_null($schedule)) {
            throw new NotFoundException($request, $response);
        }
        
        /**@var Entity\Schedule $schedule */
        
        $this->data->set('schedule', $schedule);
        
        $this->data->set('theaters', $this->get4dxTheaters());
    }
    
    /**
     * news list action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeNewsList($request, $response, $args)
    {
        $this->data->set('newsList', $this->getNewsList());
        
        $this->data->set('infoNewsList', $this->getInfoNewsList());
    }
    
    /**
     * news show action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeNewsShow($request, $response, $args)
    {
        $news = $this->em
            ->getRepository(Entity\News::class)
            ->findOneById($args['id']);
        
        if (is_null($news)) {
            throw new NotFoundException($request, $response);
        }
        
        /**@var Entity\News $news */
        
        $this->data->set('news', $news);
    }
    
    /**
     * theater action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeTheater($request, $response, $args)
    {
        $this->data->set('theaters', $this->get4dxTheaters());
    }
}
