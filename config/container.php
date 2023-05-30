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
use App\Authorization\Provider\RewardProvider as RewardAuthorizationProvider;
use App\Authorization\RewardManager as RewardAuthorizationManager;
use App\Authorization\SessionContainer as AuthorizationSessionContainer;
use App\Logger\DbalLogger;
use App\Session\SessionManager;
use App\Twig\Extension\AdvanceTicketExtension;
use App\Twig\Extension\AzureStorageExtension;
use App\Twig\Extension\CommonExtension;
use App\Twig\Extension\MembershipExtension;
use App\Twig\Extension\MotionpictureTicketExtension;
use App\Twig\Extension\NewsExtension;
use App\Twig\Extension\RewardExtension;
use App\Twig\Extension\ScheduleExtension;
use App\Twig\Extension\SeoExtension;
use App\Twig\Extension\TheaterExtension;
use App\Twig\Extension\UserExtension;
use App\User\Manager as UserManager;
use App\User\Provider\MembershipProvider as MembershipUserProvider;
use App\User\Provider\RewardProvider as RewardUserProvider;
use Blue32a\MonologGoogleCloudLoggingHandler\GoogleCloudLoggingHandler;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Laminas\Session\Config\SessionConfig;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Logger;
use Slim\App as SlimApp;
use Slim\Http\Cookies;
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
 * @return RewardAuthorizationManager
 */
$container['rewardAuth'] = static function ($container) {
    /**
     * 名称変更によるclearを想定しておく。（仕様変更などがあった場合）
     * must consist of alphanumerics, backslashes and underscores only.
     */
    $sessionContainerName = 'authorization_20230511';

    $sessionContainer = new AuthorizationSessionContainer(
        $container->get('sm')->getContainer($sessionContainerName)
    );

    $uri       = Uri::createFromEnvironment($container->get('environment'));
    $loginUrl  = $container->get('router')->fullUrlFor($uri, 'reward_login');
    $logoutUrl = $container->get('router')->fullUrlFor($uri, 'reward_logout');

    $settings = $container->get('settings')['mp_service'];

    $provider = new RewardAuthorizationProvider(
        $settings['reward_authorization_host'],
        $settings['reward_authorization_client_id'],
        $settings['reward_authorization_client_secret'],
        $settings['reward_authorization_scopes'],
        $loginUrl,
        $logoutUrl
    );

    return new RewardAuthorizationManager($provider, $sessionContainer);
};

/**
 * @return UserManager
 */
$container['um'] = static function ($container) {
    /**
     * 名称変更によるclearを想定しておく。（仕様変更などがあった場合）
     * must consist of alphanumerics, backslashes and underscores only.
     */
    $sessionContainerName = 'reward_user_20230530';

    $rewardProvider = new RewardUserProvider(
        $container->get('sm')->getContainer($sessionContainerName)
    );

    $membershipProvider = new MembershipUserProvider();

    $cookies = new Cookies($container->get('request')->getCookieParams());

    return new UserManager(
        $rewardProvider,
        $membershipProvider,
        $cookies
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
    $view->addExtension(new CommonExtension(APP_ENV));
    $view->addExtension(new MembershipExtension(
        $container->get('settings')['membership']['site_url']
    ));
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
        $container->get('um')->getUserState()
    ));
    $view->addExtension(new RewardExtension(
        $container->get('rewardAuth')
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

    if (isset($settings['google_cloud_logging'])) {
        $googleCloudLoggingSettings = $settings['google_cloud_logging'];
        $googleCloudLoggingClient   = GoogleCloudLoggingHandler::factoryLoggingClient(
            $googleCloudLoggingSettings['client_options']
        );
        $googleCloudLoggingHandler  = new GoogleCloudLoggingHandler(
            $googleCloudLoggingSettings['name'],
            $googleCloudLoggingClient,
            [],
            $googleCloudLoggingSettings['level']
        );

        $fingersCrossedSettings = $settings['fingers_crossed'];
        $logger->pushHandler(new FingersCrossedHandler(
            $googleCloudLoggingHandler,
            $fingersCrossedSettings['activation_strategy']
        ));
    }

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
    if ($settings['cache'] === 'filesystem') {
        $cache = new FilesystemCache($settings['filesystem_cache_dir']);
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
