<?php
/**
 * routes.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

use Cinemasunshine\Portal\Controller\IndexController;
use Cinemasunshine\Portal\Controller\NewsController;
use Cinemasunshine\Portal\Controller\TrailerController;

$app->get('/', IndexController::class . ':index')->setName('homepage');

$app->group('/news', function () {
    $this->get('/list', NewsController::class . ':list')->setName('news_list');
});

$app->get('/trailer', TrailerController::class . ':show')->setName('trailer');
