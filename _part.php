<?php
// Protection from directly call
if (! defined('FRAMEWORK_NS' ) ) exit;


use Niggee\Autoloader;
use Niggee\Router;
use Niggee\Authentication;

// Autoloader
require_once PROJECT_ROOT.RELATIVE_CLASS_DIR . '/Autoloader.class.php';
Autoloader::init();

// Part route info
$part_route = Router::getPartRoute();
if(empty($part_route ) ) exit;

// Authentication
if($part_route['is_secure'] ){
    if(!Authentication::isEntered() ){
        // send respons
        $response = [
            'result' => FALSE,
            'message' => 'Not authorized'
        ];
        echo json_encode($response );
        exit;
    }
}

// Send requested part
$part = PROJECT_ROOT.RELATIVE_LAYOUT_DIR.'/'.$part_route['layout'].'/part/'.$part_route['part'];
if(is_file($part ) ){
    include $part;
} else {
    $response = [
        'result' => FALSE,
        'message' => 'Requested part \''.$part.'\' not exists'
   ];
    echo json_encode($response );
}
