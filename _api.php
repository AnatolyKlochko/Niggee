<?php
// Protection from directly call
if (! defined('FRAMEWORK_NS' ) ) exit;


// Stop execution if
if ($route === FALSE) {
    // May send something...
    echo get_response_json(false, 'No such page', $data);
    // Stop execution
    exit;
}
