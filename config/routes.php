<?php
/**
 * routes.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

use Cinemasunshine\Portal\Controller\IndexController;
use Cinemasunshine\Portal\Controller\TrailerController;

$app->get('/', IndexController::class . ':index')->setName('homepage');

$app->get('/trailer', TrailerController::class . ':show')->setName('trailer');
