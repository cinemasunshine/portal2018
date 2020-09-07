<?php

/**
 * container.php
 *
 * AbstractControllerのphpdoc更新を推奨。
 *
 * @see AppAdmin\Controller\AbstractController\__call()
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

/** @var \Slim\App $app */
$container = $app->getContainer();

/**
 * Authorization Manager
 *
 * @return \App\Authorization\Manager
 */
$container['am'] = function ($container) {
    /**
     * 名称変更によるclearを想定しておく。（仕様変更などがあった場合）
     * must consist of alphanumerics, backslashes and underscores only.
     */
    $sessionContainerName = 'authorization_20200907';

    return new \App\Authorization\Manager(
        $container->get('settings')['mp_service'],
        $container->get('sm')->getContainer($sessionContainerName)
    );
};

/**
 * User Manager
 *
 * @return \App\User\Manager
 */
$container['um'] = function ($container) {
    /**
     * 名称変更によるclearを想定しておく。（仕様変更などがあった場合）
     * must consist of alphanumerics, backslashes and underscores only.
     */
    $sessionContainerName = 'user_20200907';

    return new \App\User\Manager(
        $container->get('sm')->getContainer($sessionContainerName)
    );
};

/**
 * view
 *
 * @link https://www.slimframework.com/docs/v3/features/templates.html
 *
 * @return \Slim\Views\Twig
 */
$container['view'] = function ($container) {
    $settings = $container->get('settings')['view'];

    $view = new \Slim\Views\Twig($settings['template_path'], $settings['settings']);

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri    = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    // vendor extension
    $view->addExtension(new \Twig\Extension\DebugExtension());
    $view->addExtension(new \Twig\Extensions\TextExtension());

    // app extension
    $view->addExtension(new \App\Twig\Extension\AdvanceTicketExtension());
    $view->addExtension(new \App\Twig\Extension\AzureStorageExtension(
        $container->get('bc'),
        $container->get('settings')['storage']['public_endpoint']
    ));
    $view->addExtension(new \App\Twig\Extension\CommonExtension());
    $view->addExtension(new \App\Twig\Extension\MotionpictureTicketExtension(
        $container->get('settings')['mp_service']
    ));
    $view->addExtension(new \App\Twig\Extension\NewsExtension());
    $view->addExtension(new \App\Twig\Extension\SeoExtension(
        APP_ROOT . '/data/metas.json'
    ));
    $view->addExtension(new \App\Twig\Extension\ScheduleExtension(
        $container->get('settings')['schedule']
    ));
    $view->addExtension(new \App\Twig\Extension\TheaterExtension());
    $view->addExtension(new \App\Twig\Extension\UserExtension(
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
 * @return \Monolog\Logger
 */
$container['logger'] = function ($container) {
    $settings = $container->get('settings')['logger'];

    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\PsrLogMessageProcessor());
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushProcessor(new Monolog\Processor\IntrospectionProcessor());
    $logger->pushProcessor(new Monolog\Processor\WebProcessor());
    $logger->pushProcessor(new Monolog\Processor\MemoryUsageProcessor());
    $logger->pushProcessor(new Monolog\Processor\MemoryPeakUsageProcessor());

    if (isset($settings['browser_console'])) {
        $browserConsoleSettings = $settings['browser_console'];

        $logger->pushHandler(new \Monolog\Handler\BrowserConsoleHandler(
            $browserConsoleSettings['level']
        ));
    }

    $azureBlobStorageSettings = $settings['azure_blob_storage'];
    $azureBlobStorageHandler  = new App\Logger\Handler\AzureBlobStorageHandler(
        $container->get('bc'),
        $azureBlobStorageSettings['container'],
        $azureBlobStorageSettings['blob'],
        $azureBlobStorageSettings['level']
    );

    $fingersCrossedSettings = $settings['fingers_crossed'];
    $logger->pushHandler(new Monolog\Handler\FingersCrossedHandler(
        $azureBlobStorageHandler,
        $fingersCrossedSettings['activation_strategy']
    ));

    return $logger;
};

/**
 * Doctrine entity manager
 *
 * @return \Doctrine\ORM\EntityManager
 */
$container['em'] = function ($container) {
    $settings = $container->get('settings')['doctrine'];
    $proxyDir = APP_ROOT . '/src/ORM/Proxy';

    /**
     * cacheは明示的に指定する。
     * 拡張機能(apc,memcached,redis)が有効だとそちらが使用されるので。
     * @see \Doctrine\ORM\Tools\Setup::createCacheInstance()
     */
    if ($settings['cache'] === 'wincache') {
        $cache = new \Doctrine\Common\Cache\WinCacheCache();
    } else {
        $cache = new \Doctrine\Common\Cache\ArrayCache();
    }

    /**
     * 第５引数について、他のアノテーションとの競合を避けるためSimpleAnnotationReaderは使用しない。
     * @Entity => @ORM\Entity などとしておく。
     */
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        $settings['metadata_dirs'],
        $settings['dev_mode'],
        $proxyDir,
        $cache,
        false
    );

    $config->setProxyNamespace('App\ORM\Proxy');

    $logger = new \App\Logger\DbalLogger($container->get('logger'));
    $config->setSQLLogger($logger);

    return \Doctrine\ORM\EntityManager::create($settings['connection'], $config);
};

/**
 * session manager
 *
 * @return \App\Session\SessionManager
 */
$container['sm'] = function ($container) {
    $settings = $container->get('settings')['session'];

    $config = new \Laminas\Session\Config\SessionConfig();
    $config->setOptions($settings);

    return new \App\Session\SessionManager($config);
};

/**
 * Azure Blob Storage Client
 *
 * @link https://github.com/Azure/azure-storage-php/tree/master/azure-storage-blob
 * @return \MicrosoftAzure\Storage\Blob\BlobRestProxy
 */
$container['bc'] = function ($container) {
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

    return \MicrosoftAzure\Storage\Blob\BlobRestProxy::createBlobService($connection);
};

$container['errorHandler'] = function ($container) {
    return new \App\Application\Handlers\Error(
        $container->get('logger'),
        $container->get('settings')['displayErrorDetails']
    );
};

$container['phpErrorHandler'] = function ($container) {
    return new \App\Application\Handlers\PhpError(
        $container->get('logger'),
        $container->get('settings')['displayErrorDetails']
    );
};

$container['notFoundHandler'] = function ($container) {
    return new \App\Application\Handlers\NotFound();
};

$container['notAllowedHandler'] = function ($container) {
    return new \App\Application\Handlers\NotAllowed();
};
