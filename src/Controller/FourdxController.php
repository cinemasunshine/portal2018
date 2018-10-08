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
    }
}