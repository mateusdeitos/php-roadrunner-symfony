<?php

namespace App\Order\Consumers;

use Spiral\RoadRunner\EnvironmentInterface;
use Spiral\RoadRunner\Jobs\Consumer;

class NewOrderConsumer
{
    public function run(
		object $data,
	): void
    {
		var_dump("New order with ID: $data->payload\n");
    }

    public function canServe(EnvironmentInterface $env): bool
    {
        return $env->getMode() === 'jobs';
    }
	
    public function serve(): void
    {
        $consumer = new Consumer();

        while ($task = $consumer->waitTask()) {
            try {
                // Handle and process task. Here we just print payload.
				sleep(5);
				if ($task->getName() !== self::class) {
					continue;
				}

				$this->run(json_decode($task->getPayload()));

                // Complete task.
                $task->complete();
            } catch (\Throwable $e) {
                $task->fail($e);
            }
        }
    }
}
