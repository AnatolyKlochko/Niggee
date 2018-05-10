<?php

/** R o u t i n g */

/**
 * Seek page and layout base on uri. If page will no found, gets default page with
 * default layout.
 * Note, an uri ($uri ) now is in clean format, like '', 'en', 'about', 'contacts',
 * 'ru/admin/users', etc. But uri parts array ($uripart ) contains right uri 
 * without language.
 * 
 * @param array $uripart 
 * @return array An array with 2 elements: ['page']='somepage', ['layout']='somelayout'.
 */
function get_route(array &$uripart ) : array {
    
    // Detect page name
    $page = empty($uripart ) ? DEFAULT_PAGE : implode(FG, $uripart );
    
    // Seek page layout
    $route_config = PROJECT_ROOT . RELATIVE_CONFIG_DIR . '/route.config';
    // Routes array
    $routes = json_decode(file_get_contents($route_config ), TRUE );
    // Try get page options
    $pageoption = $routes[$page];
    // Check if exists
    if(is_null($pageoption ) ) {
        // Requested page is not exists in Routes array, so view 404 page.
        $page = DEFAULT_NOTFOUNDPAGE;
        // Get again page options (at now for 404 page )
        $pageoption = $routes[$page];
    }
    // Get layout
    $layout = $pageoption['layout'];
    
    // Result array
    $result = [
        'page' => $page,
        'layout' => $layout
    ];
    
    
    //
    return $result;
    
}