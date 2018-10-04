<?php
/**
 * routes.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

use Cinemasunshine\Portal\Controller\AboutController;
use Cinemasunshine\Portal\Controller\ImaxController;
use Cinemasunshine\Portal\Controller\IndexController;
use Cinemasunshine\Portal\Controller\NewsController;
use Cinemasunshine\Portal\Controller\ScheduleController;
use Cinemasunshine\Portal\Controller\TheaterController;
use Cinemasunshine\Portal\Controller\TheaterListController;
use Cinemasunshine\Portal\Controller\TrailerController;

$app->get('/', IndexController::class . ':index')->setName('homepage');

$app->get('/company-profile', AboutController::class . ':company')->setName('company');

$app->group('/news', function () {
    $this->get('/list', NewsController::class . ':list')->setName('news_list');
    $this->get('/{id}', NewsController::class . ':show')->setName('news_show');
});

$app->group('/movie', function() {
    $this->get('/list', ScheduleController::class . ':list')->setName('schedule_list');
    $this->get('/{schedule}', ScheduleController::class . ':show')->setName('schedule_show');
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
        $this->get('/{id}', TheaterController::class . ':newsShow')->setName('theater_news_show');
    });
});

$app->get('/trailer', TrailerController::class . ':show')->setName('trailer');

$app->group('/imax', function() {
    $this->get('', ImaxController::class . ':index')->setName('imax');
    $this->get('/about', ImaxController::class . ':about')->setName('imax_about');
    $this->get('/movie/list', ImaxController::class . ':scheduleList')->setName('imax_schedule_list');
    $this->get('/movie/{schedule}', ImaxController::class . ':scheduleShow')->setName('imax_schedule_show');
    $this->get('/news/list', ImaxController::class . ':newsList')->setName('imax_news_list');
    $this->get('/news/{id}', ImaxController::class . ':newsShow')->setName('imax_news_show');
    $this->get('/theater', ImaxController::class . ':theater')->setName('imax_theater');
});
