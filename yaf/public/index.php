<?php

define('APPLICATION_PATH', dirname(__FILE__) . "/..");

require __DIR__.'/../library/mphp/metgs.php';

$application = new Yaf_Application( APPLICATION_PATH . "/config/application.ini");

$application->bootstrap()->run();

?>
