<?php
// Protection from directly call
if (! defined('FRAMEWORK_NS' ) ) exit;


// Plugin globals
global $uripart, $page, $layout, $page_content, $content;

// Load appropriate router. A router is function to get a route. This function is placed in appropriate router file. And include layout and page helper functions
load_router();  load_layout_functions();  load_page_functions();

// Get route (array with page name, layout name )
$route = get_route($uripart );

// Initialize $page and $layout globals
$page = $route['page'];  $layout = $route['layout'];

// Evaluate page (get page content and set it to $page_content global )
set_page_content($page, $page_content );

// Evalute layout (get layout content and set it to $layout_content global, result is whole content - everything from <DOCTYPE> till </html> )
set_content($layout, $content );

// While will compute page and layout content, by business logic maybe setted some response headers (include cookies )
echo_headers();

// Output whole content
echo_content();
