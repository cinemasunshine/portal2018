<?php
/**
 * TheaterController.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

use Slim\Exception\NotFoundException;

use Cinemasunshine\Portal\ORM\Entity;

/**
 * Theater controller
 */
class TheaterController extends BaseController
{
    /**
     * find by entity
     *
     * @param string $name
     * @return Entity\Theater
     * @throws NotFoundException
     */
    protected function findByEntity(string $name)
    {
        $theater = $this->em
            ->getRepository(Entity\Theater::class)
            ->findOneByName($name);
            
        if (is_null($theater)) {
            throw new NotFoundException($request, $response);
        }
        
        /**@var Entity\Theater $theater */
        
        return $theater;
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
        $theater = $this->findByEntity($args['name']);
        
        $this->data->set('theater', $theater);
    }
    
    /**
     * access action
     * 
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeAccess($request, $response, $args)
    {
        $theater = $this->findByEntity($args['name']);
        
        $this->data->set('theater', $theater);
    }
    
    /**
     * admission action
     * 
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeAdmission($request, $response, $args)
    {
        $theater = $this->findByEntity($args['name']);
        
        $this->data->set('theater', $theater);
    }
    
    /**
     * concession action
     * 
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeConcession($request, $response, $args)
    {
        $theater = $this->findByEntity($args['name']);
        
        $this->data->set('theater', $theater);
    }
    
    /**
     * floor guide action
     * 
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     * @return string|void
     */
    public function executeFloorGuide($request, $response, $args)
    {
        $theater = $this->findByEntity($args['name']);
        
        $this->data->set('theater', $theater);
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
        $theater = $this->findByEntity($args['name']);
        
        $this->data->set('theater', $theater);
        
        $this->data->set('newsList', $this->getNewsList($theater));
    }
    
    /**
     * return news list
     *
     * @param Entity\Theater $theater
     * @param int|null       $category
     * @param int|null       $limit
     * @return Entity\News[]
     */
    protected function getNewsList(Entity\Theater $theater, ?int $category = null, ?int $limit = null)
    {
        return $this->em
            ->getRepository(Entity\News::class)
            ->findByTheater($theater->getId(), $category, $limit);
    }
}
