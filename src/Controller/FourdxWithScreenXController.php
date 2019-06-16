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
