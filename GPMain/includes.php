<?php

define('DEBUG', true);
define('ROOT', dirname(__FILE__));

require_once (ROOT."/backend/database.php");

// Named autoloader
function autoload_class($class){
    include_once(ROOT. "/backend/" . $class . ".class.php");
}
spl_autoload_register('autoload_class');