<?php
/**
 * routes.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

use Cinemasunshine\Portal\Controller\AboutController;
use Cinemasunshine\Portal\Controller\FourdxController;
use Cinemasunshine\Portal\Controller\ImaxController;
use Cinemasunshine\Portal\Controller\IndexController;
use Cinemasunshine\Portal\Controller\NewsController;
use Cinemasunshine\Portal\Controller\ScheduleController;
use Cinemasunshine\Portal\Controller\ScreenXController;
use Cinemasunshine\Portal\Controller\TheaterController;
use Cinemasunshine\Portal\Controller\TheaterListController;
use Cinemasunshine\Portal\Controller\TrailerController;

use Cinemasunshine\Portal\Controller\API\ScheduleController as ScheduleApiController;

$app->get('/', IndexController::class . ':index')->setName('homepage');

$app->get('/company/', AboutController::class . ':company')->setName('company');
$app->get('/magazine/', AboutController::class . ':mailMagazine')->setName('mail_magazine');
$app->get('/mvtk/', AboutController::class . ':mvtk')->setName('mvtk');
$app->get('/app/', AboutController::class . ':officialApp')->setName('official_app');
$app->get('/online/', AboutController::class . ':onlineTicket')->setName('online_ticket');
$app->get('/privacy/', AboutController::class . ':privacy')->setName('privacy');
$app->get('/question/', AboutController::class . ':question')->setName('question');
$app->get('/sitemap/', AboutController::class . ':sitemap')->setName('sitemap');
$app->get('/special_ticket/', AboutController::class . ':specialTicket')->setName('special_ticket');
$app->get('/law/', AboutController::class . ':specificQuotient')->setName('specific_quotient');
$app->get('/sitepolicy/', AboutController::class . ':termsOfService')->setName('terms_of_service');

$app->group('/news', function () {
    $this->get('/', NewsController::class . ':list')->setName('news_list');
    $this->get('/{id}.php', NewsController::class . ':show')->setName('news_show');
});

$app->group('/movie', function() {
    $this->get('/', ScheduleController::class . ':list')->setName('schedule_list');
    $this->get('/{schedule}.php', ScheduleController::class . ':show')->setName('schedule_show');
});

$app->get('/theater/', TheaterListController::class . ':index')->setName('theater_list');
$app->get('/theater-sns/', TheaterListController::class . ':sns')->setName('theater_sns');

$app->group('/theater/{name}', function () {
    $this->get('/', TheaterController::class . ':index')->setName('theater');
    $this->get('/access/', TheaterController::class . ':access')->setName('theater_access');
    $this->get('/admission/', TheaterController::class . ':admission')->setName('theater_admission');
    $this->get('/advance_ticket/', TheaterController::class . ':advanceTicket')->setName('theater_advance_ticket');
    $this->get('/concession/', TheaterController::class . ':concession')->setName('theater_concession');
    $this->get('/floor_guide/', TheaterController::class . ':floorGuide')->setName('theater_floor_guide');
    
    $this->group('/news', function () {
        $this->get('/', TheaterController::class . ':newsList')->setName('theater_news_list');
        $this->get('/{id}.php', TheaterController::class . ':newsShow')->setName('theater_news_show');
    });
});

# ムービーウォーカー様広告として展開しているのでURLはそのまま。タイミングがあえば直す SASAKI-315
$app->get('/trailer', TrailerController::class . ':show')->setName('trailer');

$app->group('/imax', function() {
    $this->get('/', ImaxController::class . ':index')->setName('imax');
    $this->get('/about/', ImaxController::class . ':about')->setName('imax_about');
    $this->get('/movie/', ImaxController::class . ':scheduleList')->setName('imax_schedule_list');
    $this->get('/movie/{schedule}.php', ImaxController::class . ':scheduleShow')->setName('imax_schedule_show');
    $this->get('/news/', ImaxController::class . ':newsList')->setName('imax_news_list');
    $this->get('/news/{id}.php', ImaxController::class . ':newsShow')->setName('imax_news_show');
    $this->get('/theater/', ImaxController::class . ':theater')->setName('imax_theater');
});

$app->group('/4dx', function() {
    $this->get('/', FourdxController::class . ':index')->setName('4dx');
    $this->get('/about/', FourdxController::class . ':about')->setName('4dx_about');
    $this->get('/movie/', FourdxController::class . ':scheduleList')->setName('4dx_schedule_list');
    $this->get('/movie/{schedule}.php', FourdxController::class . ':scheduleShow')->setName('4dx_schedule_show');
    $this->get('/news/', FourdxController::class . ':newsList')->setName('4dx_news_list');
    $this->get('/news/{id}.php', FourdxController::class . ':newsShow')->setName('4dx_news_show');
    $this->get('/theater/', FourdxController::class . ':theater')->setName('4dx_theater');
});

$app->group('/screen-x', function() {
    $this->get('/', ScreenXController::class . ':index')->setName('screenx');
    $this->get('/about/', ScreenXController::class . ':about')->setName('screenx_about');
    $this->get('/movie/', ScreenXController::class . ':scheduleList')->setName('screenx_schedule_list');
    $this->get('/movie/{schedule}.php', ScreenXController::class . ':scheduleShow')->setName('screenx_schedule_show');
    $this->get('/news/', ScreenXController::class . ':newsList')->setName('screenx_news_list');
    $this->get('/news/{id}.php', ScreenXController::class . ':newsShow')->setName('screenx_news_show');
    $this->get('/theater/', ScreenXController::class . ':theater')->setName('screenx_theater');
});

# APIのURL設計はひとまずそのまま SASAKI-315
$app->group('/api', function() {
    $this->group('/schedule/{name}', function() {
        $this->get('', ScheduleApiController::class . ':index');
        $this->get('/{date:[\d]{4}-[\d]{2}-[\d]{2}}', ScheduleApiController::class . ':date');
    });
});