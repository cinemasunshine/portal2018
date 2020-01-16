<?php
/**
 * settings.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 *
 * @return array
 */

$settings = [];

$isDebug = in_array(APP_ENV, ['dev', 'test']);

$settings['displayErrorDetails'] = $isDebug;
$settings['addContentLengthHeader'] = false;

// view
$settings['view'] = [
    'template_path' => APP_ROOT . '/template',
    'settings' => [
        'debug' => $isDebug,
        'cache' => APP_ROOT . '/cache/view',
    ],
];

/**
 * session
 *
 * Zend-Session Configのオプションとして使用。
 *
 * @link https://docs.zendframework.com/zend-session/config/
 * @link https://github.com/phpredis/phpredis#php-session-handler
 */
$getSessionSetting = function () {
    $settings = [
        'name' => 'csportal',
        'php_save_handler' => 'redis',
        'gc_maxlifetime' => 60 * 60 * 1, // SASAKI-485
    ];

    $savePathParams = [
        // 別の用途ができた時は改めて考える
        'prefix' => 'session:',

        /**
         * 「Azure Cache for Redis のベスト プラクティス」を参考にひとまず15秒とする
         * https://docs.microsoft.com/ja-jp/azure/azure-cache-for-redis/cache-best-practices
         */
        'timeout' => 15,
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
$getLoggerSetting = function ($isDebug) {
    $settings = [
        'name' => 'app',
    ];

    if ($isDebug) {
        $settings['chrome_php'] = [
            'level' => \Monolog\Logger::DEBUG,
        ];
    }

    $settings['fingers_crossed'] = [
        'activation_strategy' => \Monolog\Logger::ERROR,
    ];

    $settings['azure_blob_storage'] = [
        'level' => \Monolog\Logger::INFO,
        'container' => 'frontend-log',
        'blob' => date('Ymd') . '.log',
    ];

    return $settings;
};

$settings['logger'] = $getLoggerSetting($isDebug);

// doctrine
$getDoctrineSetting = function ($isDebug) {
    $settings = [
        'dev_mode' => $isDebug,
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

    if (getenv('MYSQLCONNSTR_SSL') === 'true') {
        // https://docs.microsoft.com/ja-jp/azure/mysql/howto-configure-ssl
        $cafile = APP_ROOT . '/cert/BaltimoreCyberTrustRoot.crt.pem';
        $settings['connection']['driverOptions'][PDO::MYSQL_ATTR_SSL_CA] = $cafile;
    }

    return $settings;
};

$settings['doctrine'] = $getDoctrineSetting($isDebug);

// storage
$settings['storage'] = [
    'secure'  => true,
    'account' => [
        'name' => getenv('CUSTOMCONNSTR_STORAGE_NAME'),
        'key'  => getenv('CUSTOMCONNSTR_STORAGE_KEY'),
    ],
];

// Motionpicture Service
$getMpServiceSetting = function () {
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
    $scopeList = str_replace('<API_URL>', $apiUrl, $baseScopeList);
    $settings['authorization_code_scope'] = $scopeList;

    return $settings;
};

$settings['mp_service'] = $getMpServiceSetting();

// Schedule
$settings['schedule'] = [
    'env' => getenv('APPSETTING_SCHEDULE_ENV'),
];

return $settings;
