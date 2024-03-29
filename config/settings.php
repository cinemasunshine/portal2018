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
        'cache' => getenv('APPSETTING_VIEW_CACHE_DIR') ?: APP_ROOT . '/cache/view',
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
        'prefix' => 'session_v20230608:',

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

    if (in_array(APP_ENV, ['dev', '4st', 'prod'])) {
        $settings['fingers_crossed'] = [
            'activation_strategy' => Logger::ERROR,
        ];

        $settings['google_cloud_logging'] = [
            'name' => 'app',
            'level' => Logger::INFO,
            'client_options' => [
                'projectId' => getenv('GOOGLE_CLOUD_PROJECT'),
            ],
        ];
    }

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
        'filesystem_cache_dir' => getenv('APPSETTING_DOCTRINE_FILESYSTEM_CACHE_DIR') ?: APP_ROOT . '/cache/doctrine',

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
    /**
     * デフォルトとしてエミュレーターの接続情報を設定する
     * 設定されていないとビルドできないので
     * doctrine orm:generate-proxies でエラーになる（loggerで使用しているのが原因と思われる）
     */
    $defaultName = 'devstoreaccount1';
    $defaultKey  = 'Eby8vdM02xNOcqFlqUwJPLlmEtlCDXJ1OUzFT50uSRZ6IFsuFq2UVErCz4I6tq';

    $settings = [
        'account_name' => getenv('CUSTOMCONNSTR_STORAGE_NAME') ?: $defaultName,
        'account_key' => getenv('CUSTOMCONNSTR_STORAGE_KEY') ?: $defaultKey,
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

        // リワード Authorization
        'reward_authorization_host'          => getenv('APPSETTING_MP_AUTHORIZATION_CODE_HOST'),
        'reward_authorization_client_id'     => getenv('APPSETTING_MP_AUTHORIZATION_CODE_CLIENT_ID'),
        'reward_authorization_client_secret' => getenv('APPSETTING_MP_AUTHORIZATION_CODE_CLIENT_SECRET'),

        // Ticket
        'ticket_url'             => getenv('APPSETTING_MP_TICKET_URL'),
        'ticket_entrance_url'    => getenv('APPSETTING_MP_TICKET_ENTRANCE_URL'),
        'ticket_transaction_url' => getenv('APPSETTING_MP_TICKET_TRANSACTION_URL'),
    ];

    $rewardBaseScopes = [
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

    $settings['reward_authorization_scopes'] = str_replace('<API_URL>', $apiUrl, $rewardBaseScopes);

    return $settings;
};

$settings['mp_service'] = $getMpServiceSetting();

// Schedule
$settings['schedule'] = [
    'env'     => getenv('APPSETTING_SCHEDULE_ENV'),
    'api_url' => getenv('APPSETTING_SCHEDULE_API_URL'),
];

$settings['membership'] = [
    'site_url' => getenv('APPSETTING_MEMBERSHIP_SITE_URL'),
];

return $settings;
