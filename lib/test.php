<?php
//$config = json_decode(file_get_contents(__DIR__.'/config/app.config' ), true );

// Application config
while(1 ) {
    
$app_config_path = __DIR__ . '/../config/application.config';
$config = [
    'service' => [
        '[main]' => '/.service',
        'Access' => '/.service',
        'Statistics' => '/.service',
        'ZendValidator' => '/.service',
   ],
    'routing' => [
        'params_prefix' => '~',         /* example: 'www.mysite.com/page/user/list/~/page/2/' */
        'pattern' => [
            'exclude_multi_slash' => '~/{2,}~',   /* pattern to exclude (cut ) double (and more ) slashes and replace it to one slash */
            'lang' => '~(^[a-z]{2}$)|(^[a-z]{2}_[A-Z]{2}$)~' /** Language Component Pattern, it is pattern to detect language in uri */
       ],
        'layout' => [
            'default' => 'index',
            '404' => 'index',
            'error' => 'index'
       ],
        'page' => [
            'default' => 'index',
            '404' => '404',
            'error' => 'error'
       ],
    'lang' => 'en',                     /* default language */
    ]
];
file_put_contents($app_config_path, json_encode($config, JSON_PRETTY_PRINT) );

break;
}

// Routing
while( 0 ) {
    
$route_config = __DIR__ . '/../config/index.route.config';
$route = [
    'index' => [
        'layout' => 'index',
        'is_secure' => FALSE
   ],
    'about' => [
        'layout' => 'index',
        'is_secure' => FALSE
   ],
    'contact' => [
        'layout' => 'index',
        'is_secure' => FALSE
   ],
    '404' => [
        'layout' => 'index',
        'is_secure' => FALSE
   ]
];
file_put_contents($route_config, json_encode($route, JSON_PRETTY_PRINT) );

break;
}