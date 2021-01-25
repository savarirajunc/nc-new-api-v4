<?php

use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\Mvc\Dispatcher;
 
$controller_name=ucfirst($router->getControllerName());
$action_name=$router->getActionName();
$controller_prefix=$router->getControllerName();

$collection = new MicroCollection();


/*Set the main handler. ie. a controller instance*/
$collection->setHandler($controller_name.'Controller',TRUE);
/* Set a common prefix for all routes*/
$collection->setPrefix('/'.$controller_prefix);

if($action_name=="viewall"):
    $collection->get("/".$action_name,$action_name);
else:
	$collection->get("/".$action_name,$action_name);
    $collection->post("/".$action_name,$action_name);
endif;




$app->mount($collection);
$app->notFound(
    function () use ($app) {
        $app->response->setStatusCode(200, 'Not Found');
        $app->response->sendHeaders();

        $message = 'Nothing to see here. Move along....';
        $app->response->setContent($message);
        $app->response->send();
    }
);
