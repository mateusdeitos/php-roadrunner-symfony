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
    ): JsonResponse {
        if (!is_string($hashIntegration) || empty($hashIntegration)) {
            throw new BadRequestException('Invalid hashIntegration', 400);
        }

        $body = json_decode($request->getContent());
        if (!$body) {
            throw new BadRequestException('Invalid request body', 400);
        }

        $orderId = $body->orderId;
        $orderNumber = $body->orderNumber;
        $total = $body->total;
        if (empty($orderId)) {
            throw new BadRequestException('Invalid orderId', 400);
        }

        if (empty($orderNumber)) {
            throw new BadRequestException('Invalid orderNumber', 400);
        }

        if (empty($total)) {
            throw new BadRequestException('Invalid total', 400);
        }

        if (is_float($total)) {
            $total = intval($total * 100);
        }

        $newOrderProducer->run($orderId, $orderNumber, $hashIntegration, $total);

        return $this->json(['message' => 'Notification received']);
    }
}
