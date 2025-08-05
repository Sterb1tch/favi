<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\DTO\OrderData;
use App\DTO\Response\OrderCreatedResponse;
use App\DTO\UpdateOrderDeliveryDateData;
use App\Facade\Order\OrderFacadeInterface;
use PHPUnit\Framework\Attributes\DataProvider;

class OrderFacadeTest extends BaseIntegrationTest
{
    private OrderFacadeInterface $orderFacade;

    #[DataProvider('getValidRequestFiles')]
    public function testCreateOrder(string $filePath): void
    {
        /** @var OrderData $object */
        $object = $this->getObject($this->getFileContent(__DIR__ . '/' . $filePath), OrderData::class);
        $orderResponse = $this->orderFacade->createOrder($object);

        self::assertInstanceOf(OrderCreatedResponse::class, $orderResponse);
    }

    public function testDeliveryDate(): void
    {
        $date = new \DateTime('now +1 week');

        /** @var OrderData $objectOrder */
        $objectOrder = $this->getObject(
            $this->getFileContent(__DIR__ . '/' . self::EXAMPLE_FILE_CREATE_REQUEST_FIRST),
            OrderData::class
        );
        $orderResponse = $this->orderFacade->createOrder($objectOrder);

        self::assertInstanceOf(OrderCreatedResponse::class, $orderResponse);

        $objectDate = $this->getFileContent(__DIR__ . '/' . self::EXAMPLE_FILE_UPDATE_DELIVERY_DATE, [
            '{DATETIME}' => $date->format('Y-m-d H:i:s'),
        ]);
        /** @var UpdateOrderDeliveryDateData $objectUpdateDeliveryDate */
        $objectUpdateDeliveryDate = $this->getObject($objectDate, UpdateOrderDeliveryDateData::class);

        $updateDeliveryResponse = $this->orderFacade->updateDeliveryDate($orderResponse->id, $objectUpdateDeliveryDate);
        $updatedOrder = $updateDeliveryResponse->order;

        self::assertSame($updatedOrder->getExpectedDeliveryDate()->format(\DATE_ATOM), $date->format(\DATE_ATOM));
    }

    protected function setUp(): void
    {
        parent::setUp();

        /** @var OrderFacadeInterface $orderFacade */
        $orderFacade = self::getContainer()->get(OrderFacadeInterface::class);
        $this->orderFacade = $orderFacade;
    }
}
