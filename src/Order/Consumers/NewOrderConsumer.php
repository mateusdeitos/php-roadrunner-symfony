<?php

namespace App\Order\Consumers;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class NewOrderConsumer
{
	public function __construct(private EntityManagerInterface $entityManager) {}

    public function run(
        Order $order,
	): void {
		$this->entityManager->persist($order);
		$this->entityManager->flush();

		echo "Order {$order->getId()} created\n";
    }
}
