<?php
/**
 * 
 */
function initDefaultPage(stdClass $route)
{
    // Global config
    $config = $GLOBALS['config'];
    
    // Init route object
    $route->page = $config['routing']['page']['default'];
    $route->layout = $config['routing']['layout']['default'];
    $route->is_secure = false;
}

/**
 * 
 */
function initNotFoundPage(stdClass $route)
{
    // Global config
    $config = $GLOBALS['config'];
    
    // Init route object
    $route->page = $config['routing']['page']['404'];
    $route->layout = $config['routing']['layout']['404'];
    $route->is_secure = false;
}