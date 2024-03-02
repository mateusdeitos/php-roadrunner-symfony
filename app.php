<?php

require 'vendor/autoload.php';

use App\Dispatcher\HttpDispatcher;
use App\Dispatcher\QueueDispatcher;
use App\Kernel;
use Spiral\RoadRunner\Environment;


$kernel = new Kernel('dev', true);
$kernel->boot();

$dispatchers = [
    new HttpDispatcher(),
	new QueueDispatcher()
];

// Create environment
$env = Environment::fromGlobals();


// Execute dispatcher that can serve the request
foreach ($dispatchers as $dispatcher) {
    if ($dispatcher->canServe($env)) {
        $dispatcher->serve($kernel);
    }
}
