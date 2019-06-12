<?php
/**
 * FourdxWithScreenXController.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Controller;

/**
 * FourdxWithScreenX controller
 *
 * 4DX with ScreenX特設サイト
 */
class FourdxWithScreenXController extends SpecialSiteController
{
    const SPECIAL_SITE_ID = 4;
    
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
