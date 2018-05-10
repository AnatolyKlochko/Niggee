<?php
// Protection from directly call
if (! defined('FRAMEWORK_NS' ) ) exit;
/**
 * Examples:
 * '/page/admin/comments'                       default format 'html'
 * '/page/admin/comments/~/format/json'         
 * 
 */



// Load consts, globals, functions
$lib = PROJECT_ROOT . RELATIVE_LIB_DIR;
require $lib . '/layout.functions.php';



// Routing
// - array of allowed routers
$allowed_router = ['Page'];
// - get routers
$router = require($lib . '/router.php');
// - get global route object
$route = $GLOBALS['route']->main;
// - match page
$result = false;
foreach ($allowed_router as &$r) {
    // If router is present
    if (isset($router[$r])) {
        // Match page
        $result = $router[$r]($route);
        // If page is matched, leave cycle
        if ($result) {
            break;
        }
    }
}
// - if matching fail, init with default settings
if (!$result) {
    if ($route->uri === 'page') {
        initDefaultPage($route);
    } else {
        initNotFoundPage($route);
    }
}

// Rendering
// - page content
getContentTo(
    getPagePath(REQUEST_TYPE['PAGE'], $route->page),
    $page_content
);

// Response
$format = isset($route->params_arr['format']) ? $route->params_arr['format'] : 'html';

// Output response
if ('html' === $format) {
    echo $page_content;
} elseif ('json' === $format) {
    echo getResponseJson(true, '', $page_content);
}
