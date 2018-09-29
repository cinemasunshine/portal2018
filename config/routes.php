<?php
/**
 * routes.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

use Cinemasunshine\Portal\Controller\IndexController;
use Cinemasunshine\Portal\Controller\NewsController;
use Cinemasunshine\Portal\Controller\TheaterListController;
use Cinemasunshine\Portal\Controller\TrailerController;

$app->get('/', IndexController::class . ':index')->setName('homepage');

$app->group('/news', function () {
    $this->get('/list', NewsController::class . ':list')->setName('news_list');
    $this->get('/{id}', NewsController::class . ':show')->setName('news_show');
});

$app->group('/theater', function () {
    $this->get('/list', TheaterListController::class . ':index')->setName('theater_list');
    $this->get('/sns', TheaterListController::class . ':sns')->setName('theater_sns');
});

$app->get('/trailer', TrailerController::class . ':show')->setName('trailer');
