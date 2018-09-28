<?php
/**
 * NewsController.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

use Slim\Exception\NotFoundException;

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
    
    /**
     * show action
     * 
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeShow($request, $response, $args)
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
}
