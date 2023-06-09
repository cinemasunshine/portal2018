<?php

declare(strict_types=1);

use App\Controller\AboutController;
use App\Controller\AuthorizationController;
use App\Controller\Development\DoctrineController;
use App\Controller\FourdxController;
use App\Controller\FourdxScreenController;
use App\Controller\ImaxController;
use App\Controller\IndexController;
use App\Controller\NewsController;
use App\Controller\OyakoCinemaController;
use App\Controller\ScheduleController;
use App\Controller\ScreenXController;
use App\Controller\Theater\AboutController as TheaterAboutController;
use App\Controller\Theater\AdvanceTicketController as TheaterAdvanceTicketController;
use App\Controller\Theater\IndexController as TheaterIndexController;
use App\Controller\Theater\NewsController as TheaterNewsController;
use App\Controller\Theater\ScheduleController as TheaterScheduleController;
use App\Controller\TheaterListController;
use Psr\Http\Message\ResponseInterface;
use Slim\App as SlimApp;

// phpcs:disable SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration
/** @var SlimApp $app */
// phpcs:enable

$app->get('/', IndexController::class . ':index')->setName('homepage');

$app->get('/login/', AuthorizationController::class . ':login')->setName('login');
$app->get('/logout/', AuthorizationController::class . ':logout')->setName('logout');

$app->get('/company/', AboutController::class . ':company')->setName('company');
$app->get('/magazine/', AboutController::class . ':mailMagazine')->setName('mail_magazine');
$app->get('/mvtk/', AboutController::class . ':mvtk')->setName('mvtk');
$app->get('/app/', AboutController::class . ':officialApp')->setName('official_app');
$app->get('/online/', AboutController::class . ':onlineTicket')->setName('online_ticket');
$app->get('/privacy/', AboutController::class . ':privacy')->setName('privacy');
$app->get('/reward/', AboutController::class . ':reward')->setName('reward');
$app->get('/cs_reward/', AboutController::class . ':cs_reward')->setName('cs_reward');
$app->get('/question/', AboutController::class . ':question')->setName('question');
$app->get('/sitemap/', AboutController::class . ':sitemap')->setName('sitemap');
$app->get('/special_ticket/', AboutController::class . ':specialTicket')->setName('special_ticket');
$app->get('/law/', AboutController::class . ':specificQuotient')->setName('specific_quotient');
$app->get('/sitepolicy/', AboutController::class . ':termsOfService')->setName('terms_of_service');

$app->get('/oyako_cinema/', OyakoCinemaController::class . ':index')->setName('oyako_cinema');

$app->group('/news', function (): void {
    $this->get('/', NewsController::class . ':list')->setName('news_list');
    $this->get('/{id:[0-9]+}.php', NewsController::class . ':show')->setName('news_show');
});

$app->group('/movie', function (): void {
    $this->get('/', ScheduleController::class . ':list')->setName('schedule_list');
    $this->get('/{schedule:[0-9]+}.php', ScheduleController::class . ':show')->setName('schedule_show');

    $this->group('/{schedule:[0-9]+}', function (): void {
        $this->get('/news/', ScheduleController::class . ':news')->setName('schedule_news');
        $this->get('/news/{news:[0-9]+}.php', ScheduleController::class . ':newsShow')->setName('schedule_news_show');
    });
});

$app->get('/theater/', TheaterListController::class . ':index')->setName('theater_list');
$app->get('/theater-sns/', TheaterListController::class . ':sns')->setName('theater_sns');

$app->group('/theater/{name}', function (): void {
    $this->get('/', TheaterIndexController::class . ':show')->setName('theater');
    $this->get('/access/', TheaterAboutController::class . ':access')->setName('theater_access');
    $this->get('/admission/', TheaterAboutController::class . ':admission')->setName('theater_admission');
    $this->get('/advance_ticket/', TheaterAdvanceTicketController::class . ':index')->setName('theater_advance_ticket');
    $this->get('/concession/', TheaterAboutController::class . ':concession')->setName('theater_concession');
    $this->get('/floor_guide/', TheaterAboutController::class . ':floorGuide')->setName('theater_floor_guide');

    $this->group('/news', function (): void {
        $this->get('/', TheaterNewsController::class . ':index')->setName('theater_news_list');
        $this->get('/{id:[0-9]+}.php', TheaterNewsController::class . ':show')->setName('theater_news_show');
    });

    $this->group('/movie', function (): void {
        $this->get('/', TheaterScheduleController::class . ':index')->setName('theater_schedule');
        $this->get('/{schedule:[0-9]+}.php', TheaterScheduleController::class . ':show')
            ->setName('theater_schedule_show');
    });
});

$app->group('/imax', function (): void {
    $this->get('/', ImaxController::class . ':index')->setName('imax');
    $this->get('/about/', ImaxController::class . ':about')->setName('imax_about');
    $this->get('/movie/', ImaxController::class . ':scheduleList')->setName('imax_schedule_list');
    $this->get('/movie/{schedule:[0-9]+}.php', ImaxController::class . ':scheduleShow')->setName('imax_schedule_show');
    $this->get('/news/', ImaxController::class . ':newsList')->setName('imax_news_list');
    $this->get('/news/{id:[0-9]+}.php', ImaxController::class . ':newsShow')->setName('imax_news_show');
    $this->get('/theater/', ImaxController::class . ':theater')->setName('imax_theater');
});

$app->group('/4dx', function (): void {
    $this->get('/', FourdxController::class . ':index')->setName('4dx');
    $this->get('/about/', FourdxController::class . ':about')->setName('4dx_about');
    $this->get('/movie/', FourdxController::class . ':scheduleList')->setName('4dx_schedule_list');
    $this->get('/movie/{schedule:[0-9]+}.php', FourdxController::class . ':scheduleShow')->setName('4dx_schedule_show');
    $this->get('/news/', FourdxController::class . ':newsList')->setName('4dx_news_list');
    $this->get('/news/{id:[0-9]+}.php', FourdxController::class . ':newsShow')->setName('4dx_news_show');
    $this->get('/theater/', FourdxController::class . ':theater')->setName('4dx_theater');
});

$app->group('/screen-x', function (): void {
    $this->get('/', ScreenXController::class . ':index')->setName('screenx');
    $this->get('/about/', ScreenXController::class . ':about')->setName('screenx_about');
    $this->get('/movie/', ScreenXController::class . ':scheduleList')->setName('screenx_schedule_list');
    $this->get('/movie/{schedule:[0-9]+}.php', ScreenXController::class . ':scheduleShow')
        ->setName('screenx_schedule_show');
    $this->get('/news/', ScreenXController::class . ':newsList')->setName('screenx_news_list');
    $this->get('/news/{id:[0-9]+}.php', ScreenXController::class . ':newsShow')->setName('screenx_news_show');
    $this->get('/theater/', ScreenXController::class . ':theater')->setName('screenx_theater');
});

$app->group('/4dx-screen', function (): void {
    $this->get('/', FourdxScreenController::class . ':index')->setName('4dx_screen');
    $this->get('/about/', FourdxScreenController::class . ':about')->setName('4dx_screen_about');
    $this->get('/movie/', FourdxScreenController::class . ':scheduleList')
        ->setName('4dx_screen_schedule_list');
    $this->get('/movie/{schedule:[0-9]+}.php', FourdxScreenController::class . ':scheduleShow')
        ->setName('4dx_screenschedule_show');
    $this->get('/news/', FourdxScreenController::class . ':newsList')->setName('4dx_screen_news_list');
    $this->get('/news/{id:[0-9]+}.php', FourdxScreenController::class . ':newsShow')
        ->setName('4dx_screen_news_show');
    $this->get('/theater/', FourdxScreenController::class . ':theater')->setName('4dx_screen_theater');
});

/**
 * @link https://cloud.google.com/appengine/docs/standard/php-gen2/configuring-warmup-requests?hl=ja
 */
$app->get('/_ah/warmup', function ($request, $response): ResponseInterface {
    // ウォームアップ リクエストを有効にするための最低限の実装
    $response->getBody()->write('Warmup successful');

    return $response;
});

/**
 * 開発用ルート設定
 *
 * IPアドレスなどでアクセス制限することを推奨します。
 */
$app->group('/dev', function (): void {
    $this->group('/doctrine', function (): void {
        $this->get('/cache/stats', DoctrineController::class . ':cacheStats');
        $this->get('/cache/{target:query|metadata}/clear', DoctrineController::class . ':cacheClear');
    });
});
