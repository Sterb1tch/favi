<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\DTO\OrderData;
use App\DTO\UpdateOrderDeliveryDateData;
use App\Entity\Order;
use App\Exception\OrderAlreadyExistsException;
use App\Exception\OrderDeliveryDateException;
use App\Exception\OrderNotFoundException;
use App\Service\Order\OrderServiceInterface;
use PHPUnit\Framework\Attributes\DataProvider;

class OrderServiceTest extends BaseIntegrationTest
{
    private OrderServiceInterface $orderService;

    #[DataProvider('getValidRequestFiles')]
    public function testCreateOrder(string $filePath): void
    {
        /** @var OrderData $objectData */
        $objectData = $this->getObject($this->getFileContent(__DIR__ . '/' . $filePath), OrderData::class);
        $order = $this->orderService->createOrder($objectData);

        self::assertInstanceOf(Order::class, $order);
    }

    public function testDeliveryDate(): void
    {
        $date = new \DateTime('now +1 week');

        /** @var OrderData $objectOrder */
        $objectOrder = $this->getObject(
            $this->getFileContent(__DIR__ . '/' . self::EXAMPLE_FILE_CREATE_REQUEST_FIRST),
            OrderData::class
        );
        $order = $this->orderService->createOrder($objectOrder);

        self::assertInstanceOf(Order::class, $order);

        $objectDate = $this->getFileContent(__DIR__ . '/' . self::EXAMPLE_FILE_UPDATE_DELIVERY_DATE, [
            '{DATETIME}' => $date->format('Y-m-d H:i:s'),
        ]);
        /** @var UpdateOrderDeliveryDateData $objectUpdateDeliveryDate */
        $objectUpdateDeliveryDate = $this->getObject($objectDate, UpdateOrderDeliveryDateData::class);

        $this->orderService->updateExpectedDeliveryDate($order->getId(), $objectUpdateDeliveryDate);

        self::assertSame($order->getExpectedDeliveryDate()->format(\DATE_ATOM), $date->format(\DATE_ATOM));
    }

    public function testOrderAlreadyExists(): void
    {
        /** @var OrderData $objectFirst */
        $objectFirst = $this->getObject(
            $this->getFileContent(__DIR__ . '/' . self::EXAMPLE_FILE_CREATE_REQUEST_FIRST),
            OrderData::class
        );
        $this->orderService->createOrder($objectFirst);

        $this->expectException(OrderAlreadyExistsException::class);

        /** @var OrderData $orderSecond */
        $orderSecond = $this->getObject(
            $this->getFileContent(__DIR__ . '/' . self::EXAMPLE_FILE_CREATE_REQUEST_FIRST),
            OrderData::class
        );
        $this->orderService->createOrder($orderSecond);
    }

    public function testOrderNotFound(): void
    {
        $fileContent = $this->getFileContent(__DIR__ . '/' . self::EXAMPLE_FILE_CREATE_REQUEST_FIRST, [
            '{DATETIME}' => (new \DateTime('now +1 week'))->format('Y-m-d H:i:s'),
        ]);
        /** @var UpdateOrderDeliveryDateData $object */
        $object = $this->getObject($fileContent, UpdateOrderDeliveryDateData::class);

        $this->expectException(OrderNotFoundException::class);
        $this->orderService->updateExpectedDeliveryDate(1, $object);
    }

    public function testDeliveryDateException(): void
    {
        /** @var OrderData $objectOrder */
        $objectOrder = $this->getObject(
            $this->getFileContent(__DIR__ . '/' . self::EXAMPLE_FILE_CREATE_REQUEST_FIRST),
            OrderData::class
        );
        $order = $this->orderService->createOrder($objectOrder);

        self::assertInstanceOf(Order::class, $order);

        $objectDate = $this->getFileContent(__DIR__ . '/' . self::EXAMPLE_FILE_UPDATE_DELIVERY_DATE, [
            '{DATETIME}' => (new \DateTime('-1 week'))->format('Y-m-d H:i:s'),
        ]);
        /** @var UpdateOrderDeliveryDateData $objectUpdateDeliveryDate */
        $objectUpdateDeliveryDate = $this->getObject($objectDate, UpdateOrderDeliveryDateData::class);

        $this->expectException(OrderDeliveryDateException::class);
        $this->orderService->updateExpectedDeliveryDate($order->getId(), $objectUpdateDeliveryDate);
    }

    protected function setUp(): void
    {
        parent::setUp();

        /** @var OrderServiceInterface $orderService */
        $orderService = self::getContainer()->get(OrderServiceInterface::class);
        $this->orderService = $orderService;
    }
}
