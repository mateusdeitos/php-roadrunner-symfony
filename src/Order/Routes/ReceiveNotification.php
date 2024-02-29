<?php

namespace App\Order\Routes;

use App\Order\Producers\NewOrderProducer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReceiveNotification extends AbstractController
{
    #[Route(
        '/orders/receive_notification/{hashIntegration}',
        methods: ["POST"],
        name: 'receive_notification',
        format: 'json'
    )]
    public function receiveNotification(
		string $hashIntegration, 
		Request $request,
		NewOrderProducer $newOrderProducer
	): JsonResponse
    {
        if (!is_string($hashIntegration) || empty($hashIntegration)) {
            throw new BadRequestException('Invalid hashIntegration', 400);
        }

        $body = $request->getContent();
		
		$newOrderProducer->run(json_decode($body, true)['orderId']);

        return $this->json(['message' => 'Notification received']);
    }
}
