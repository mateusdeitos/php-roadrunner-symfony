<?php

require 'vendor/autoload.php';

use App\Dispatcher\HttpDispatcher;
use App\Order\Consumers\NewOrderConsumer;
use Spiral\RoadRunner\Environment;

$dispatchers = [
    new HttpDispatcher(),
	new NewOrderConsumer()
];

// Create environment
$env = Environment::fromGlobals();

// Execute dispatcher that can serve the request
foreach ($dispatchers as $dispatcher) {
    if ($dispatcher->canServe($env)) {
        $dispatcher->serve();
    }
}
