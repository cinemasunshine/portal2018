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
$getDoctrineSetting = function () {
    $settings = [
        'dev_mode' => (APP_ENV === 'dev'),
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

$settings['doctrine'] = $getDoctrineSetting();

// storage
$settings['storage'] = [
    'secure'  => true,
    'account' => [
        'name' => getenv('CUSTOMCONNSTR_STORAGE_NAME'),
        'key'  => getenv('CUSTOMCONNSTR_STORAGE_KEY'),
    ],
];

// movie walker ad
$getMovieWalakerAdSetting = function () {
    $settings = [
        'support' => (getenv('APPSETTING_MV_AD') === 'true'),
        'page' => [],
        'theater' => [],
    ];
    
    // nameとdiv_idの割り振り基準が不明なのでデータ構造は適宜変更する。
    $settings['page'] = [
        // TOP
        1 => [
            'name'   => '/22524478/sunshine_top_336_280',
            'div_id' => 'div-gpt-ad-1538623317895-0',
        ],
    ];
    
    $settings['theater'] = [
        // 池袋
        1 => [
            'name'   => '/22524478/sunshine_ikebukuro_336_280',
            'div_id' => 'div-gpt-ad-1538619229001-0',
        ],
        
        // 平和島
        2 => [
            'name'   => '/22524478/sunshine_heiwajima_336_280',
            'div_id' => 'div-gpt-ad-1538619100223-0',
        ],
        
        // 沼津
        6 => [
            'name'   => '/22524478/sunshine_numazu_336_280',
            'div_id' => 'div-gpt-ad-1538622822641-0',
        ],
        
        // 北島
        7 => [
            'name'   => '/22524478/sunshine_kitajima_336_280',
            'div_id' => 'div-gpt-ad-1538619781081-0',
        ],
        
        // 衣山
        8 => [
            'name'   => '/22524478/sunshine_kinuyama_336_280',
            'div_id' => 'div-gpt-ad-1538619692600-0',
        ],
        
        // 大街道
        9 => [
            'name'   => '/22524478/sunshine_okaido_336_280',
            'div_id' => 'div-gpt-ad-1538622926843-0',
        ],
        
        // 重信
        12 => [
            'name'   => '/22524478/sunshine_shigenobu_336_280',
            'div_id' => 'div-gpt-ad-1538623145179-0',
        ],
        
        // 土浦
        13 => [
            'name'   => '/22524478/sunshine_tsuchiura_336_280',
            'div_id' => 'div-gpt-ad-1538623413599-0',
        ],
        
        // かほく
        14 => [
            'name'   => '/22524478/sunshine_kahoku_336_280',
            'div_id' => 'div-gpt-ad-1538619593716-0',
        ],
        
        // エミフルMASAKI
        15 => [
            'name'   => '/22524478/sunshine_masaki_336_280',
            'div_id' => 'div-gpt-ad-1538619880605-0',
        ],
        
        // 大和郡山
        16 => [
            'name'   => '/22524478/sunshine_yamatokoriyama_336_280',
            'div_id' => 'div-gpt-ad-1538623516564-0',
        ],
        
        // 下関
        17 => [
            'name'   => '/22524478/sunshine_shimonoseki_336_280',
            'div_id' => 'div-gpt-ad-1538623234933-0',
        ],
        
        // 姶良
        18 => [
            'name'   => '/22524478/sunshine_aira_336_280',
            'div_id' => 'div-gpt-ad-1538617598924-0',
        ],
        
        // ユーカリが丘
        19 => [
            'name'   => '/22524478/sunshine_yukarigaoka_336_280',
            'div_id' => 'div-gpt-ad-1538623639711-0',
        ],
    ];
    
    return $settings;
};

$settings['mw_ad'] = $getMovieWalakerAdSetting();

// Motionpicture Online Ticket
$getMpOnlineTicketSetting = function () {
    $settings = [
        'url'          => getenv('APPSETTING_MP_TICKET_URL'),
        'entrance_url' => getenv('APPSETTING_MP_TICKET_ENTRANCE_URL'),
    ];
    
    return $settings;
};

$settings['mp_ticket'] = $getMpOnlineTicketSetting();

// Coasystems Schedule
$settings['coa_schedule'] = [
    'env' => getenv('APPSETTING_COA_SCHEDULE'),
];

return $settings;
