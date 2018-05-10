<?php

/** O u t p u t */

/**
 * 
 */
function echo_headers() : void
{
    echo_cookies();
}

/**
 * 
 */
function echo_cookies() : void
{
    
}

/**
 * 
 */
function echo_content()
{
    // global $content; foreach-filters: apply_filter(clear spaces )... clear_spaces\($content )
    echo $GLOBALS['content'];
}

/** Language */
function echo_lang()
{
    $route = $GLOBALS['route'];
    // 'en', 'ru'
    echo $route->lang;    
}

/**
 * Description
 */
function echo_description()
{
    global $description;
    echo $description;
}

/**
 * Author
 */
function echo_author()
{
    global $author;
    echo $author;   
}

/**
 * Title
 */
function set_title($t, $tp = null, $ts = null )
{
    global $title, $title_splitter, $title_postfix;
   
    // A whole title looks like 'Home - Health Blog, Wellness', 'About - Health Blog, Wellness'
    $title = $t; // 'Home'
    if (!is_null($tp)) {
        $title_postfix = $tp; // 'Health Blog, Wellness'
    }
    if (!is_null($ts)) {
        $title_splitter = $ts; // ' - '
    }
}
function echo_title()
{
    global $title, $title_splitter, $title_postfix;
    
    // 'Home - Healt Blog, Wellness', 'About - Healt Blog, Wellness'
    echo $title . $title_splitter . $title_postfix;    
}


/** O u t p u t: s t y l e s */

/** Identifiers for SPA */

/**
 * Gets an ID to detect custom layout styles block on client side. Only for SPA support.
 * 
 * @return string ID
 */
function layout_styles_id() : string {
    return 'custom-layout-styles';
}
/**
 * Gets an ID to detect custom page styles block on client side. Only for SPA support.
 * 
 * @return string ID
 */
function page_styles_id() : string {
    return 'custom-page-styles';
}
/**
 * Gets an ID to detect page content block on client side. Only for SPA support.
 * 
 * @return string ID
 */
function page_content_id() : string {
    return 'page-content';
}
/**
 * Gets an ID to detect custom layout script block on client side. Only for SPA support.
 * 
 * @return string ID
 */
function layout_script_id() : string {
    return 'custom-layout-script';
}
/**
 * Gets an ID to detect custom page script block on client side. Only for SPA support.
 * 
 * @return string ID
 */
function page_script_id() : string {
    return 'custom-page-script';
}
/**
 * 
 */
function echo_identifiers() { ?>
    <script>
        var customLayoutStylesID = '<?= layout_styles_id() ?>';
        var customPageStylesID = '<?= page_styles_id() ?>';
        var pageContentID = '<?= page_content_id() ?>';
        var customLayoutScriptID = '<?= layout_script_id() ?>';
        var customPageScriptID = '<?= page_script_id() ?>';
    </script>
<?php
}


/**
 * 
 */
function get_layout_styles_tag(string $layout = NULL )
{
    
}

/**
 * 
 */
function get_layout_styles_url(string $layout = NULL )
{
    
}

/**
 * Echo custom layout styles with <style> tag.
 * 
 * Exists difference between echo_styles and inc_styles, for last will run PHP parser
 * end if within file exists php code, it will be executed. Echo just gets content
 * and output it without perform parsing and executing.
 * 
 * @param string $layout A layout name.
 * 
 * @return void
 */
function echo_layout_styles(string $layout = NULL )
{
    // Layout
    if (empty($layout)) {
        $layout  = getLayoutName();
    }
    
    // Echo styles
    $path = getPagePath(REQUEST_TYPE['STYLE'], null, $layout);
    if (file_exists($path)) { ?>
        <style><?= file_get_contents($path) ?></style><?php
    }
}

/** Get layout name */
function getLayoutName() : string
{
    $layout  = $GLOBALS['route']->layout;
    if (empty($layout)) {
        $layout  = $GLOBALS['config']['routing']['layout']['default'];
    }
    return $layout;   
}

/**
 * Includes layout part.
 * 
 * @param string $part A layout part. Naming: 'part-{layout}-{partname}'. Examples: 'part-index-menu', 'part-index-footer'
 * 
 * @return void
 */
function inc_part(string $part, string $layout = NULL)
{
    // Layout
    if (empty($layout)) {
        $layout = getLayoutName();
    }
    
    // Include part
    include getPagePath(REQUEST_TYPE['PART'], $part, $layout);
}

/**
 * Echo layout part.
 * 
 * @param string $part A layout part. Naming: 'part-{layout}-{partname}'. Examples: 'part-index-menu', 'part-index-footer'
 * 
 * @return void
 */
function echo_part(string $part, string $layout = NULL)
{
    // Layout
    if (empty($layout)) {
        $layout = getLayoutName();
    }
    
    // Echo part
    $path = getPagePath(REQUEST_TYPE['PART'], $part, $layout);
    if (file_exists($path)) {
        echo file_get_contents($path);
    }
}

/** O u t p u t: s c r i p t s */

/**
 * 
 */
function get_layout_script_tag(string $layout = NULL)
{
    
}

/**
 * 
 */
function get_layout_script_url(string $layout = NULL)
{
    
}

/**
 * Echo custom layout script with <script> tag.
 * 
 * Exists difference between echo_script and inc_script, for last will run PHP parser
 * end if within file exists php code, it will be executed. Echo just gets content
 * and output it without perform parsing and executing.
 * 
 * @param string $layout A layout name.
 * 
 * @return void
 */
function echo_layout_script(string $layout = NULL)
{
    // Page
    if (empty($layout)) {
        $layout  = getLayoutName();
    }
    
    // Echo script
    $path = getPagePath(REQUEST_TYPE['SCRIPT'], null, $layout);
    if (file_exists($path)) { ?>
        <script><?= file_get_contents($path) ?></script><?php
    }
}