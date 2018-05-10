<?php

/** R o u t i n g */

/**
 * 
 */
function get_route(array &$uripart ) : array {
    
    // Detect layout name
    $layout = empty($uripart ) ? DEFAULT_LAYOUT : (isset($uripart[1] ) ? $uripart[1] : NULL );
    
    // Seek page
    $page;
    if(empty($uripart ) ) {
        $page = DEFAULT_PAGE;
    } elseif(isset($uripart[2] ) ) {
        $c = count($uripart );
        $page = '';
        for($i = 2; $i < $c; $i++ ) {
            $page .= $uripart[$i] . FG;
        }
        $page = rtrim($page, FG );
    }
    
    
    // Result array
    $result = [
        'page' => $page,
        'layout' => $layout
   ];
    
    
    // Get result
    return $result;
    
}