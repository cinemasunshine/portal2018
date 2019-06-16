<?php
/**
 * FourdxWithScreenXController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

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
