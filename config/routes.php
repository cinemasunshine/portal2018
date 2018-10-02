<?php
/**
 * routes.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

use Cinemasunshine\Portal\Controller\AboutController;
use Cinemasunshine\Portal\Controller\IndexController;
use Cinemasunshine\Portal\Controller\NewsController;
use Cinemasunshine\Portal\Controller\TheaterController;
use Cinemasunshine\Portal\Controller\TheaterListController;
use Cinemasunshine\Portal\Controller\TitleController;
use Cinemasunshine\Portal\Controller\TrailerController;

$app->get('/', IndexController::class . ':index')->setName('homepage');

$app->get('/company-profile', AboutController::class . ':company')->setName('company');

$app->group('/news', function () {
    $this->get('/list', NewsController::class . ':list')->setName('news_list');
    $this->get('/{id}', NewsController::class . ':show')->setName('news_show');
});

$app->group('/title', function() {
    $this->get('/list', TitleController::class . ':list')->setName('title_list');
    $this->get('/{id}', TitleController::class . ':show')->setName('title_show');
});

$app->get('/theater-list', TheaterListController::class . ':index')->setName('theater_list');
$app->get('/theater-sns-list', TheaterListController::class . ':sns')->setName('theater_sns');

$app->group('/theater/{name}', function () {
    $this->get('', TheaterController::class . ':index')->setName('theater');
    $this->get('/access', TheaterController::class . ':access')->setName('theater_access');
    $this->get('/admission', TheaterController::class . ':admission')->setName('theater_admission');
    $this->get('/concession', TheaterController::class . ':concession')->setName('theater_concession');
    $this->get('/facility', TheaterController::class . ':floorGuide')->setName('theater_floor_guide');
    
    $this->group('/news', function () {
        $this->get('/list', TheaterController::class . ':newsList')->setName('theater_news_list');
    });
});

$app->get('/trailer', TrailerController::class . ':show')->setName('trailer');
