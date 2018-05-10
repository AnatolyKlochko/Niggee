<?php

/** R o u t i n g */

/**
 * 
 */
function get_route(array &$uripart ) : array {
    
    // Detect page name
    $page;
    if(isset($uripart[1] ) ) {
        $c = count($uripart );
        $page = '';
        for($i = 1; $i < $c; $i++ ) {
            $page .= $uripart[$i] . FG;
        }
        $page = rtrim($page, FG );
    }
    
    
    // Result array
    $result = [
        'page' => $page,
        'layout' => NULL
   ];
    
    
    //
    return $result;
    
}