<?php

namespace App\HealthCheck;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PingController extends AbstractController
{
    #[Route(
        '/ping',
        methods: ["GET"],
        name: 'ping',
        format: 'json'
    )]
    public function ping(): JsonResponse
    {
        return $this->json(['pong' => true]);
    }
}
