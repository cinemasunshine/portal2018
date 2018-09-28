<?php
/**
 * NewsController.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

use Cinemasunshine\Portal\ORM\Entity;

/**
 * News controller
 */
class NewsController extends GeneralController
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
        $this->data->set('newsList', $this->getNewsList());
        
        $this->data->set('campaings', $this->getCampaigns(self::PAGE_ID));
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
            ->findByPage(self::PAGE_ID);
    }
}
