<?php

namespace App\Order\Routes;

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
    public function receiveNotification(string $hashIntegration, Request $request): JsonResponse
    {
        if (!is_string($hashIntegration) || empty($hashIntegration)) {
            throw new BadRequestException('Invalid hashIntegration', 400);
        }

        $body = $request->getContent();

        return $this->json(['body' => json_decode($body)]);
    }
}
