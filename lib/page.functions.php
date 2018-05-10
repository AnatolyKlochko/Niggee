<?php
/** Styles */

/**
 * 
 */
function get_page_styles_tag(string $page = NULL )
{
    
}
/**
 * 
 */
function get_page_styles_url(string $page = NULL )
{
    
}
/**
 * 
 */
function echo_page_styles(string $page = NULL)
{
    // Page
    if (empty($page)) {
        $page  = getPageName();
    }
    
    // Echo styles
    $path = getPagePath(REQUEST_TYPE['STYLE'], $page);
    if (file_exists($path)) { ?>
        <style><?= file_get_contents($path) ?></style><?php
    }
}


/** Content */

/**
 * Echo page output.
 */
function echo_page() {
    echo $GLOBALS['page_content'];
}


/** Scripts */

/** Get layout name */
function getPageName() : string
 {
    $page  = $GLOBALS['route']->page;
    if (empty($page)) {
        $page  = $GLOBALS['config']['routing']['page']['default'];
    }
    return $page;
}

/**
 * 
 */
function get_page_script_tag(string $page = NULL )
{
    
}

/**
 * 
 */
function get_page_script_url(string $page = NULL )
{
    
}

/**
 * 
 */
function echo_page_script(string $page = NULL )
{
    // Page
    if (empty($page)) {
        $page  = getPageName();
    }
    
    // Echo scripts
    $path = getPagePath(REQUEST_TYPE['SCRIPT'], $page);
    if (file_exists($path)) { ?>
        <script><?= file_get_contents($path) ?></script><?php
    }
}