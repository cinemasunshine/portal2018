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
class NewsController extends BaseController
{
    const PAGE_ID = 1;
    
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
