<?php

declare(strict_types=1);

/**
 * AbstractControllerのphpdoc更新を推奨。
 *
 * @see AppAdmin\Controller\AbstractController\__call()
 */

use App\Application\Handlers\Error;
use App\Application\Handlers\NotAllowed;
use App\Application\Handlers\NotFound;
use App\Application\Handlers\PhpError;
use App\Authorization\Manager as AuthorizationManager;
use App\Logger\DbalLogger;
use App\Logger\Handler\AzureBlobStorageHandler;
use App\Session\SessionManager;
use App\Twig\Extension\AdvanceTicketExtension;
use App\Twig\Extension\AzureStorageExtension;
use App\Twig\Extension\CommonExtension;
use App\Twig\Extension\MotionpictureTicketExtension;
use App\Twig\Extension\NewsExtension;
use App\Twig\Extension\ScheduleExtension;
use App\Twig\Extension\SeoExtension;
use App\Twig\Extension\TheaterExtension;
use App\Twig\Extension\UserExtension;
use App\User\Manager as UserManager;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\WinCacheCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Laminas\Session\Config\SessionConfig;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Logger;
use Slim\App as SlimApp;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;

// phpcs:disable SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration
/** @var SlimApp $app */
// phpcs:enable

$container = $app->getContainer();

/**
 * Authorization Manager
 *
 * @return AuthorizationManager
 */
$container['am'] = static function ($container) {
    /**
     * 名称変更によるclearを想定しておく。（仕様変更などがあった場合）
     * must consist of alphanumerics, backslashes and underscores only.
     */
    $sessionContainerName = 'authorization_20200907';

    return new AuthorizationManager(
        $container->get('settings')['mp_service'],
        $container->get('sm')->getContainer($sessionContainerName)
    );
};

/**
 * User Manager
 *
 * @return UserManager
 */
$container['um'] = static function ($container) {
    /**
     * 名称変更によるclearを想定しておく。（仕様変更などがあった場合）
     * must consist of alphanumerics, backslashes and underscores only.
     */
    $sessionContainerName = 'user_20200907';

    return new UserManager(
        $container->get('sm')->getContainer($sessionContainerName)
    );
};

/**
 * view
 *
 * @link https://www.slimframework.com/docs/v3/features/templates.html
 *
 * @return Twig
 */
$container['view'] = static function ($container) {
    $settings = $container->get('settings')['view'];

    $view = new Twig($settings['template_path'], $settings['settings']);

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri    = Uri::createFromEnvironment(new Environment($_SERVER));
    $view->addExtension(new TwigExtension($router, $uri));

    // vendor extension
    $view->addExtension(new DebugExtension());
    $view->addExtension(new StringExtension());

    // app extension
    $view->addExtension(new AdvanceTicketExtension());
    $view->addExtension(new AzureStorageExtension(
        $container->get('bc'),
        $container->get('settings')['storage']['public_endpoint']
    ));
    $view->addExtension(new CommonExtension());
    $view->addExtension(new MotionpictureTicketExtension(
        $container->get('settings')['mp_service']
    ));
    $view->addExtension(new NewsExtension());
    $view->addExtension(new SeoExtension(
        APP_ROOT . '/data/metas.json'
    ));
    $view->addExtension(new ScheduleExtension(
        $container->get('settings')['schedule']
    ));
    $view->addExtension(new TheaterExtension(
        APP_ROOT . '/data/theater/keywords.json'
    ));
    $view->addExtension(new UserExtension(
        $container->get('um'),
        $container->get('am')
    ));

    return $view;
};

/**
 * logger
 *
 * @link https://github.com/Seldaek/monolog
 *
 * @return Logger
 */
$container['logger'] = static function ($container) {
    $settings = $container->get('settings')['logger'];

    $logger = new Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\PsrLogMessageProcessor());
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushProcessor(new Monolog\Processor\IntrospectionProcessor());
    $logger->pushProcessor(new Monolog\Processor\WebProcessor());
    $logger->pushProcessor(new Monolog\Processor\MemoryUsageProcessor());
    $logger->pushProcessor(new Monolog\Processor\MemoryPeakUsageProcessor());

    if (isset($settings['browser_console'])) {
        $browserConsoleSettings = $settings['browser_console'];

        $logger->pushHandler(new BrowserConsoleHandler(
            $browserConsoleSettings['level']
        ));
    }

    $azureBlobStorageSettings = $settings['azure_blob_storage'];
    $azureBlobStorageHandler  = new AzureBlobStorageHandler(
        $container->get('bc'),
        $azureBlobStorageSettings['container'],
        $azureBlobStorageSettings['blob'],
        $azureBlobStorageSettings['level']
    );

    $fingersCrossedSettings = $settings['fingers_crossed'];
    $logger->pushHandler(new FingersCrossedHandler(
        $azureBlobStorageHandler,
        $fingersCrossedSettings['activation_strategy']
    ));

    return $logger;
};

/**
 * Doctrine entity manager
 *
 * @return EntityManager
 */
$container['em'] = static function ($container) {
    $settings = $container->get('settings')['doctrine'];
    $proxyDir = APP_ROOT . '/src/ORM/Proxy';

    /**
     * cacheは明示的に指定する。
     * 拡張機能(apc,memcached,redis)が有効だとそちらが使用されるので。
     *
     * @see \Doctrine\ORM\Tools\Setup::createCacheInstance()
     */
    if ($settings['cache'] === 'wincache') {
        $cache = new WinCacheCache();
    } else {
        $cache = new ArrayCache();
    }

    /**
     * 第５引数について、他のアノテーションとの競合を避けるためSimpleAnnotationReaderは使用しない。
     *
     * @Entity => @ORM\Entity などとしておく。
     */
    $config = Setup::createAnnotationMetadataConfiguration(
        $settings['metadata_dirs'],
        $settings['dev_mode'],
        $proxyDir,
        $cache,
        false
    );

    $config->setProxyNamespace('App\ORM\Proxy');

    $logger = new DbalLogger($container->get('logger'));
    $config->setSQLLogger($logger);

    return EntityManager::create($settings['connection'], $config);
};

/**
 * session manager
 *
 * @return SessionManager
 */
$container['sm'] = static function ($container) {
    $settings = $container->get('settings')['session'];

    $config = new SessionConfig();
    $config->setOptions($settings);

    return new SessionManager($config);
};

/**
 * Azure Blob Storage Client
 *
 * @link https://github.com/Azure/azure-storage-php/tree/master/azure-storage-blob
 *
 * @return BlobRestProxy
 */
$container['bc'] = static function ($container) {
    $settings = $container->get('settings')['storage'];
    $protocol = $settings['secure'] ? 'https' : 'http';

    $connection = sprintf(
        'DefaultEndpointsProtocol=%s;AccountName=%s;AccountKey=%s;',
        $protocol,
        $settings['account_name'],
        $settings['account_key']
    );

    if ($settings['blob_endpoint']) {
        $connection .= sprintf('BlobEndpoint=%s;', $settings['blob_endpoint']);
    }

    return BlobRestProxy::createBlobService($connection);
};

$container['errorHandler'] = static function ($container) {
    return new Error(
        $container->get('logger'),
        $container->get('settings')['displayErrorDetails']
    );
};

$container['phpErrorHandler'] = static function ($container) {
    return new PhpError(
        $container->get('logger'),
        $container->get('settings')['displayErrorDetails']
    );
};

$container['notFoundHandler'] = static function ($container) {
    return new NotFound();
};

$container['notAllowedHandler'] = static function ($container) {
    return new NotAllowed();
};
