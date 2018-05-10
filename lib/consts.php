<?php
/**
 * Define framework constants.
 */

/** Project Namespace */
define('PROJECT_NS', 'WellnessBlog' );

/** Relative paths */    
/** Usage PROJECT_ROOT . RELATIVE_LOG_DIR */
define('RELATIVE_LOG_DIR', '/logs' );
/** Usage PROJECT_ROOT . RELATIVE_CONFIG_DIR */
define('RELATIVE_CONFIG_DIR', '/config' );
/** Usage PROJECT_ROOT . RELATIVE_LIB_DIR */
define('RELATIVE_LIB_DIR', '/lib' );
/** Usage PROJECT_ROOT . RELATIVE_EXT_LIB_DIR */
define('RELATIVE_EXT_LIB_DIR', '/vendor' );
/** Usage PROJECT_ROOT . RELATIVE_CSS_DIR */
define('RELATIVE_CSS_DIR', '/css' );
/** Usage PROJECT_ROOT . RELATIVE_JS_DIR */
define('RELATIVE_JS_DIR', '/js' );
/** Usage PROJECT_ROOT . RELATIVE_JS_DIR */
define('RELATIVE_PLUGIN_DIR', '/plugin' );

/** Request Type Prefixes. Is using to detect request type and run appropriate handler type */
const REQUEST_TYPE = [
    'API'=>'api',           // 'api' request type ('/api/user/list' )
    'PAGE'=>'page',         // 'page' request type ('/page/admin/comments' )
    'PART'=>'part',         // 'part' request type ('/part/index/menu' )
    'LAYOUT'=>'layout',     // 'layout' request type ('/layout/index' )
    'STYLE'=>'style',       // 'style' request type ('/style/~/layout/index/page/index/format/json' )
    'SCRIPT'=>'script',     // 'script' request type ('/script/~/layout/index', '/script/~/layout/index/page/index/format/json' )
    'PLUGIN'=>'plugin',     // 'plugin' request type ('/plugin/access', '/plugin/access/api/options/update' )
    'INDEX'=>'index'        // common request
];
/** Means 'FileGlue', ex: api-view-category.php */
define('FG', '-' );
/** Means 'FileEXTention', ex: php */
define('FEXT', '.php' );
/** http | https */
define('REQUEST_PROTOCOL', strtolower(explode('/', $_SERVER['SERVER_PROTOCOL'] )[0] ) );