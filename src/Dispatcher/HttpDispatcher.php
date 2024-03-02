<?php

namespace App\Dispatcher;

use App\Kernel;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Spiral\RoadRunner\EnvironmentInterface;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Runtime\SymfonyRuntime;

final class HttpDispatcher
{
    public function canServe(EnvironmentInterface $env): bool
    {
        return $env->getMode() === 'http';
    }

    public function serve(Kernel $kernel): void
    {
        $factory = new Psr17Factory();
        $worker = new PSR7Worker(Worker::create(), $factory, $factory, $factory);

		$env = dirname(__DIR__, 2) . '/.env';
		// var_dump([
		// 	'path' => $env,
		// 	'exists' => file_exists($env),
		// 	'readable' => is_readable($env)
		// ]);			

		(new Dotenv("APP_ENV", "APP_DEBUG"))
			->usePutenv(false)
			->bootEnv($env);
		
        while (true) {
			try {
				$request = $worker->waitRequest();
                if ($request === null) {
					break;
                }
            } catch (\Throwable $e) {
                $worker->respond(new Response(400, body: $e->getMessage()));
                continue;
            }


			$response = $kernel->handle((new HttpFoundationFactory())->createRequest($request));
			ob_start();
			$response->sendContent();
			$content = ob_get_clean();
			
            try {
                // Handle request and return response.
                $worker->respond(new Response(
					$response->getStatusCode(), 
					$response->headers->all(), 
					$content
				));
            } catch (\Throwable $e) {
                $worker->respond(new Response(500, [], $e->getMessage()));
                $worker->getWorker()->error((string)$e);
            }
        }
    }
}
