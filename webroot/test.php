<?php
error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS . '..' . DS); // ../
define('WEBROOT', __DIR__ . DS );
define('VIEW_DIR', ROOT . 'View' . DS);
define('CONF_DIR', ROOT . 'Config' . DS);
define('VENDOR_DIR', ROOT . 'vendor' . DS);
define('START_YEAR', 2016);
define('END_YEAR', 2037);
define('TIME_ZONE', 'Europe/Kiev');

require_once VENDOR_DIR . 'autoload.php';

spl_autoload_register(function($className) {
    $file = ROOT . str_replace('\\', DS, "{$className}.php");
    
    if (!file_exists($file)) {
        throw new \Exception("{$file} not found");
    }
    
    require_once $file;
});

date_default_timezone_set(TIME_ZONE);
$hours=22;
if ($hours > 11) {
    echo 'pm';
if($hours>12) {
    echo $hours-12;}
}else {
    echo 'am';
    echo $hours;
}
echo $hours;


