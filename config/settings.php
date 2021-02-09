<?php

declare(strict_types=1);

/**
 * @return array
 */

use Monolog\Logger;

$settings = [];

$settings['displayErrorDetails'] = APP_DEBUG;

$settings['addContentLengthHeader'] = false;

// view
$settings['view'] = [
    'template_path' => APP_ROOT . '/template',
    'settings' => [
        'debug' => APP_DEBUG,
        'cache' => APP_ROOT . '/cache/view',
    ],
];

/**
 * session
 *
 * laminas-session configのオプションとして使用。
 *
 * @link https://docs.laminas.dev/laminas-session/config/
 * @link https://github.com/phpredis/phpredis#php-session-handler
 */
$getSessionSetting = static function () {
    $settings = [
        'name' => 'csportal',
        'php_save_handler' => 'redis',
        'gc_maxlifetime' => 60 * 60 * 1, // SASAKI-485
    ];

    $savePathParams = [
        /**
         * セッションに関して変更があった場合に適宜変更する。
         */
        'prefix' => 'session_v20200327:',

        /**
         * 「Azure Cache for Redis のベスト プラクティス」を参考にひとまず15秒とする
         * https://docs.microsoft.com/ja-jp/azure/azure-cache-for-redis/cache-best-practices
         */
        'timeout' => 15,

        /**
         * セッションで使用するデータベース。
         * 他の用途では別のデータベースを使用する予定。
         * ただしprefixの変更で対応できない場合は別のデータベースに変更する可能性もある。
         */
        'database' => 0,
    ];

    if (getenv('CUSTOMCONNSTR_REDIS_AUTH')) {
        $savePathParams['auth'] = getenv('CUSTOMCONNSTR_REDIS_AUTH');
    }

    $savePath = 'tcp://'
        . getenv('CUSTOMCONNSTR_REDIS_HOST')
        . ':'
        . getenv('CUSTOMCONNSTR_REDIS_PORT');

    $savePath .= '?' . http_build_query($savePathParams, '', '&');

    $settings['save_path'] = $savePath;

    return $settings;
};

$settings['session'] = $getSessionSetting();

// logger
$getLoggerSetting = static function () {
    $settings = ['name' => 'app'];

    if (APP_DEBUG) {
        $settings['browser_console'] = [
            'level' => Logger::DEBUG,
        ];
    }

    $settings['fingers_crossed'] = [
        'activation_strategy' => Logger::ERROR,
    ];

    $settings['azure_blob_storage'] = [
        'level' => Logger::INFO,
        'container' => 'frontend-log',
        'blob' => date('Ymd') . '.log',
    ];

    return $settings;
};

$settings['logger'] = $getLoggerSetting();

/**
 * doctrine
 *
 * @link https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/configuration.html#installation-and-configuration
 *
 * @return array
 */
$getDoctrineSetting = static function () {
    $settings = [
        /**
         * ビルドに影響するのでtrueにするのはローカルモードに限定しておく。
         *
         * truneの場合
         * * キャッシュはメモリ内で行われる（ArrayCache）
         * * Proxyオブジェクトは全てのリクエストで再作成される
         *
         * falseの場合
         * * 指定のキャッシュが使用されるかAPC、Xcache、Memcache、Redisの順で確認される
         * * Proxyクラスをコマンドラインから明示的に作成する必要がある
         */
        'dev_mode' => (APP_ENV === 'local'),

        'cache' => getenv('APPSETTING_DOCTRINE_CACHE') ?: 'array',
        'metadata_dirs' => [APP_ROOT . '/src/ORM/Entity'],

        'connection' => [
            'driver'   => 'pdo_mysql',
            'host'     => getenv('MYSQLCONNSTR_HOST'),
            'port'     => getenv('MYSQLCONNSTR_PORT'),
            'dbname'   => getenv('MYSQLCONNSTR_NAME'),
            'user'     => getenv('MYSQLCONNSTR_USER'),
            'password' => getenv('MYSQLCONNSTR_PASSWORD'),
            'charset'  => 'utf8mb4',
            'driverOptions'  => [],

            // @link https://m-p.backlog.jp/view/SASAKI-246
            'serverVersion' => '5.7',
        ],
    ];

    $cafile = getenv('MYSQLCONNSTR_SSL_CA');

    if ($cafile) {
        $settings['connection']['driverOptions'][PDO::MYSQL_ATTR_SSL_CA] = $cafile;
    }

    return $settings;
};

$settings['doctrine'] = $getDoctrineSetting();

// storage
$getStorageSettings = static function () {
    $settings = [
        'account_name' => getenv('CUSTOMCONNSTR_STORAGE_NAME'),
        'account_key' => getenv('CUSTOMCONNSTR_STORAGE_KEY'),
    ];

    $settings['secure'] = (getenv('CUSTOMCONNSTR_STORAGE_SECURE') !== 'false');

    $settings['blob_endpoint'] = getenv('CUSTOMCONNSTR_STORAGE_BLOB_ENDPOINT') ?: null;

    $settings['public_endpoint'] = getenv('CUSTOMCONNSTR_STORAGE_PUBLIC_ENDOPOINT') ?: null;

    return $settings;
};

$settings['storage'] = $getStorageSettings();

// Motionpicture Service
$getMpServiceSetting = static function () {
    $settings = [
        // API
        'api_host' => getenv('APPSETTING_MP_API_HOST'),

        // 認可トークンの期限バッファー（秒）
        'authorization_token_expiration_buffer' => 60 * 5,

        // Authorization Code Grant
        'authorization_code_host'          => getenv('APPSETTING_MP_AUTHORIZATION_CODE_HOST'),
        'authorization_code_client_id'     => getenv('APPSETTING_MP_AUTHORIZATION_CODE_CLIENT_ID'),
        'authorization_code_client_secret' => getenv('APPSETTING_MP_AUTHORIZATION_CODE_CLIENT_SECRET'),

        // Client Credentials Grant
        'cliennt_credentials_host'          => getenv('APPSETTING_MP_CLIENT_CREDENTIALS_HOST'),
        'cliennt_credentials_client_id'     => getenv('APPSETTING_MP_CLIENT_CREDENTIALS_CLIENT_ID'),
        'cliennt_credentials_client_secret' => getenv('APPSETTING_MP_CLIENT_CREDENTIALS_CLIENT_SECRET'),

        // Ticket
        'ticket_url'          => getenv('APPSETTING_MP_TICKET_URL'),
        'ticket_entrance_url' => getenv('APPSETTING_MP_TICKET_ENTRANCE_URL'),
    ];

    $baseScopeList = [
        'phone',
        'openid',
        'email',
        'aws.cognito.signin.user.admin',
        'profile',
        '<API_URL>/transactions',
        '<API_URL>/events.read-only',
        '<API_URL>/organizations.read-only',
        '<API_URL>/orders.read-only',
        '<API_URL>/places.read-only',
        '<API_URL>/people.contacts',
        '<API_URL>/people.creditCards',
        '<API_URL>/people.ownershipInfos.read-only',
    ];

    $apiUrl = 'https://' . $settings['api_host'];

    $settings['authorization_code_scope'] = str_replace('<API_URL>', $apiUrl, $baseScopeList);

    return $settings;
};

$settings['mp_service'] = $getMpServiceSetting();

// Schedule
$settings['schedule'] = [
    'env'     => getenv('APPSETTING_SCHEDULE_ENV'),
    'api_url' => getenv('APPSETTING_SCHEDULE_API_URL'),
];

return $settings;
