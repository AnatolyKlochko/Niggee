<?php
/**
 * For 'layout' type handler mode has value : 'spa' or not. Because every request
 * to site root will redirect to 'layout' handler. And every 'layout' request will
 * handled in accordance with app.config default settings.
 * But 'layout' requests with parameters will override default app.config settings.
 * Request to site root is handling in accordance with app.config.
 * 
 * Examples:
 * '/layout/index'                              layout without page
 * '/layout/index/about'                        0-type,1-layout,2,3,4,...-page
 * '/layout/index/about/~/format/json'
 * 
 * There are two things need to be resolved:
 * - response format: html | json       : defines by URI parameters
 * (default: html )
 * - only layout | layout with page     : defines by app.config | URI parameters
 * (default: layout with page )
 * 
 */

// Protection from directly call
if (! defined('FRAMEWORK_NS' ) ) exit;


// Plugin globals
global $config, $uripart, $uriprms, $page, $layout, $page_content, $layout_content, $content;

// Load appropriate router. A router is function to get a route. This function is placed in appropriate router file. And include layout and page helper functions
load_router();  load_layout_functions();  load_page_functions();

// Get route (array with page name, layout name )
$route = get_route($uripart );

// Initialize $page and $layout globals
$page = $route['page'];  $layout = $route['layout'];

// If layout is not matchd
if(is_null($layout ) ) exit;

// Get content
if(is_null($page ) ) {
    
    // Only layout content without page
    set_layout_content($layout, $layout_content );
    
} else {
    
    // Evaluate page (get page content and set it to $page_content global )
    set_page_content($page, $page_content );

    // Evalute layout (get layout content and set it to $layout_content global, result is whole content - everything from <DOCTYPE> till </html> )
    set_content($layout, $content );
    
    // While will compute page and layout content, by business logic maybe setted some response headers (include cookies )
    echo_headers();
    
}

// Format
$format = preg_match('~format/([a-z]{4})/?~U', $uriprms, $matches ) === 1 ? $matches[1] : $config['spa']['layout']['response-format'];

// Response
if('html' === $format ) {
    if(is_null($page ) ) {
        echo $layout_content;
    } else {
        echo $content;
    }
} elseif('json' === $format ) {
    if(is_null($page ) ) {
        echo get_response_json(true, NULL, $layout_content );
    } else {
        echo get_response_json(true, NULL, $content );
    }
}
