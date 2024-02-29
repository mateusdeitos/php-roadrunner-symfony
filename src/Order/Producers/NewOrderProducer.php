<?php

namespace App\Order\Producers;

use Spiral\Goridge\RPC\RPC;
use Spiral\RoadRunner\Jobs\Jobs;

class NewOrderProducer
{
    public function run(
		int $orderId,
	): void
    {
		$jobs = new Jobs(RPC::create('tcp://127.0.0.1:6001'));
		$queue = $jobs->connect('default');
		$task = $queue->create(\App\Order\Consumers\NewOrderConsumer::class, json_encode(["payload" => $orderId]));
		$queue->dispatch($task);
    }
}
