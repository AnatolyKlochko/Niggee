<?php
/** 
 * 
 * 
 */
// Protection from directly call
if(! defined('FRAMEWORK_NS' ) ) exit;

spl_autoload_register(function($requested ) {
    // Native objects
    do {
        // classes
        include PROJECT_ROOT.RELATIVE_LIB_DIR.'/'.$requested.'.class.php';
        if (class_exists($requested ) ) return TRUE;
        
    } while(0 );
} );
