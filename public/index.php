<?php
date_default_timezone_set('Asia/Kolkata');
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Http\Request;

error_reporting(E_ALL);
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Handle routes
     */
    include APP_PATH . '/config/router.php';

    /**
     * Read services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    $app =new Micro();
    include APP_PATH .'/config/service_loader.php';
    $app->handle($_SERVER['REQUEST_URI']);	
    /**
     * Handle the request
     */
    //$application = new \Phalcon\Mvc\Application($di);

   // echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());

} catch (\Exception $e) {
    // echo $e->getMessage() . '<br>';
    // echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
