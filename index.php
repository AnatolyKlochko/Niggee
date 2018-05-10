<?php
$reqUri = &$_SERVER['REQUEST_URI'];

// Protection: too long URI
if (strlen($reqUri) > 300 ) exit;


// Development mode. WARNING: You must change mode to FALSE in production.
if (TRUE) {
    error_reporting(E_ALL & ~E_NOTICE );
    ini_set("display_errors", 1 );
} else {
    ini_set("display_errors", 0 );
}



/**
 * Framework Namespace. Is using to protect from directly calls in subfiles and 
 * in autoloader for quickly determine framework classes.
 */
define('FRAMEWORK_NS', 'Niggee');

/** Site Root */
define('PROJECT_ROOT', __DIR__);


// Common Engine Functions
require_once PROJECT_ROOT.'/lib/functions.php';

preInit();

init();

run();
