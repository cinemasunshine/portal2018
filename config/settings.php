<?php
/**
 * settings.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 * 
 * @return array
 */

$settings = [];

$settings['displayErrorDetails'] = (APP_ENV === 'dev');
$settings['addContentLengthHeader'] = false;

// view
$settings['view'] = [
    'template_path' => APP_ROOT . '/template',
    'settings' => [
        'debug' => (APP_ENV === 'dev'),
        'cache' => APP_ROOT . '/cache/view',
    ],
];

// logger
$getLoggerSetting = function() {
    $settings = [
        'name' => 'app',
    ];
    
    $settings['chrome_php'] = [
        'level' => \Monolog\Logger::DEBUG,
    ];
    
    return $settings;
};

$settings['logger'] = $getLoggerSetting();

// doctrine
$getDoctrineSetting = function() {
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
$getMovieWalakerAdSetting = function() {
    $settings = [
        'page' => [],
    ];
    
    // slotとidの割り振り基準が不明なのでデータ構造は適宜変更する。
    if (APP_ENV === 'dev') {
        $slots = [
            1 => '/22524478/sunshine_top_336_280',
        ];
        $settings['page']['slots'] = $slots;
        
        $ids = [
            1 => 'div-gpt-ad-1538623317895-0',
        ];
        $settings['page']['ids'] = $ids;
        
    } else if (APP_ENV === 'prod') {
        throw new \Exception('todo');
    }
    
    return $settings;
};

$settings['mw_ad'] = $getMovieWalakerAdSetting();

// Motionpicture Ticket
$getMotionpictureTicketSetting = function() {
    $settings = [];
    
    if (APP_ENV === 'prod') {
        $settings['url'] = 'https://ticket-cinemasunshine.com';
    } else {
        $settings['url'] = 'https://sskts-frontend-development.azurewebsites.net';
    }
    
    return $settings;
};

$settings['mp_ticket'] = $getMotionpictureTicketSetting();

// Coasystems Schedule
$settings['coa_schedule'] = [
    'env' => getenv('CUSTOMCONNSTR_COA_SCHEDULE'),
];

return $settings;
