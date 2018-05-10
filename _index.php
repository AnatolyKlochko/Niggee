<?php
// Protection from directly call
if (! defined('FRAMEWORK_NS' ) ) exit;



// Load consts, globals, functions
$lib = PROJECT_ROOT . RELATIVE_LIB_DIR;
//require $lib . '/index.consts.php';
require $lib . '/index.globals.php';
require $lib . '/index.functions.php';
require $lib . '/page.functions.php';
require $lib . '/layout.functions.php';



// Routing
// - array of allowed routers
$allowed_router = ['Index'];
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
    if ($route->uri === '/') {
        initDefaultPage($route);
    } else {
        initNotFoundPage($route);
    }
}



// Rendering
$page_content = &$GLOBALS['page_content'];
$content = &$GLOBALS['content'];
// - page content
getContentTo(
    getPagePath(REQUEST_TYPE['PAGE'], $route->page),
    $page_content
);
// - layout content
getContentTo(
    getPagePath(REQUEST_TYPE['LAYOUT'], null, $route->layout),
    $content
);

// While will compute page and layout content, by business logic maybe setted some response headers (include cookies )
echo_headers();

// Output whole content
echo_content();
