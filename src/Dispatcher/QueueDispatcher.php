<?php

namespace App\Dispatcher;

use App\Kernel;
use Spiral\RoadRunner\EnvironmentInterface;
use Spiral\RoadRunner\Jobs\Consumer;
use Symfony\Component\Dotenv\Dotenv;

final class QueueDispatcher
{
	public function canServe(EnvironmentInterface $env): bool
    {
        return $env->getMode() === 'jobs';
    }
	
    public function serve(Kernel $kernel): void
    {
        $consumer = new Consumer();
		
		$env = dirname(__DIR__, 2) . '/.env';
		// var_dump([
		// 	'path' => $env,
		// 	'exists' => file_exists($env),
		// 	'readable' => is_readable($env)
		// ]);			

		(new Dotenv("APP_ENV", "APP_DEBUG"))
			->usePutenv(false)
			->bootEnv($env);

        while ($task = $consumer->waitTask()) {
            try {
                // Handle and process task. Here we just print payload.
				$handler = $task->getName();
				$instance = $kernel->getContainer()->get($handler);

				$instance->run(unserialize($task->getPayload()));

                // Complete task.
                $task->complete();
            } catch (\Throwable $e) {
                $task->fail($e);
            }
        }
    }
}
