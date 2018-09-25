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

return $settings;
