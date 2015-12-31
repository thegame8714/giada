<?php
/**
 * __autoload function.
 *
 * @access public
 * @param mixed $name
 * @return void
 */
function __autoload($name) {
        $name = strtolower($name);
        $ext = '.class.php';
        $path = dirname(__FILE__)."/includes/";
        $file = $path . $name . $ext;

        if (file_exists( $file )) {
                require_once $file;
                return true;
    }
    return false;
}


// instantiate required classes

new Core;
new Shortcodes;
new Works;