<?php

namespace App\Dispatcher;

use App\Kernel;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Spiral\RoadRunner\EnvironmentInterface;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

final class HttpDispatcher
{
    public function canServe(EnvironmentInterface $env): bool
    {
        return $env->getMode() === 'http';
    }

    public function serve(): void
    {
        $factory = new Psr17Factory();
        $worker = new PSR7Worker(Worker::create(), $factory, $factory, $factory);
		
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

			$kernel = new Kernel('dev', true);
			$kernel->boot();
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
