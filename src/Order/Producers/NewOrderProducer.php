<?php

namespace App\Order\Producers;

use App\Entity\Order;
use App\Order\Consumers\NewOrderConsumer;
use Spiral\Goridge\RPC\RPC;
use Spiral\RoadRunner\Jobs\Jobs;
use Spiral\RoadRunner\Jobs\OptionsInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class NewOrderProducer
{
	public function __construct(private NewOrderConsumer $newOrderConsumer) {}
	
    public function run(
		string $orderId,
		string $orderNumber,
		string $hashIntegration,
		int $total
	): void
    {
		$jobs = new Jobs(RPC::create('tcp://127.0.0.1:6001'));
		$queue = $jobs->connect('default');

		$order = new Order();
		$order->setExternalId(strval($orderId));
		$order->setNumber(strval($orderNumber));
		$order->setIntegrationRef($this->getIntegrationRef($hashIntegration));
		$order->setTotalInCents($total);
		$order->setCreatedAt(new \DateTimeImmutable());
		$order->setUpdatedAt(new \DateTimeImmutable());

		$task = $queue->create(
			\App\Order\Consumers\NewOrderConsumer::class, 
			serialize($order),
			new class implements OptionsInterface {
				public function getDelay(): int
				{
					return 10;
				}

				public function getPriority(): int
				{
					return OptionsInterface::DEFAULT_PRIORITY;
				}

				public function getAutoAck(): bool
				{
					return OptionsInterface::DEFAULT_AUTO_ACK;
				}
			}
		);
		$queue->dispatch($task);
    }

	private function getIntegrationRef(string $hashIntegration): int
	{
		$integrationRef = match ($hashIntegration) {
			'mercadolivre' => 8,
			'shopee' => 12,
			'amazon' => 95,
			default => throw new BadRequestException('Invalid hashIntegration', 400),
		};

		return $integrationRef;
	}
}
