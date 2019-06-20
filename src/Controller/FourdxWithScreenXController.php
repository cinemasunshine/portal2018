<?php
/**
 * FourdxWithScreenXController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

use Slim\Exception\NotFoundException;

use Cinemasunshine\Portal\ORM\Entity;

/**
 * FourdxWithScreenX controller
 *
 * 4DX with ScreenX特設サイト
 */
class FourdxWithScreenXController extends SpecialSiteController
{
    const SPECIAL_SITE_ID = 4;
    
    /**
     * return Special Site theaters
     *
     * @return Entity\Theater[]
     */
    protected function getSpecialSiteTheaters()
    {
        return $this->em
            ->getRepository(Entity\Theater::class)
            ->findBySpecialSite(self::SPECIAL_SITE_ID);
    }
    
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
        
        $this->data->set('theaters', $this->getSpecialSiteTheaters());
        
        $this->data->set('screeningSchedules', $this->getScreeningSchedules());
        
        $this->data->set('soonSchedules', $this->getSoonSchedules());
        
        $this->data->set('campaigns', $this->getCampaigns());
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
        $this->data->set('theaters', $this->getSpecialSiteTheaters());
        
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
            ->findScreeningFor4dxWithScreenX();
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
            ->findSoonFor4dxWithScreenX();
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
        
        $this->data->set('theaters', $this->getSpecialSiteTheaters());
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
     * return news list
     *
     * @param int|null $limit
     * @return Entity\News[]
     */
    protected function getNewsList(?int $limit = null)
    {
        return $this->em
            ->getRepository(Entity\News::class)
            ->findBy4DXWithScreenX($limit);
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
     * theater action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeTheater($request, $response, $args)
    {
        $this->data->set('theaters', $this->getSpecialSiteTheaters());
    }
}
