<?php

declare(strict_types=1);

namespace App\Controller\V1\Order;

use App\DTO\OrderData;
use App\DTO\UpdateOrderDeliveryDateData;
use App\Facade\Order\OrderFacadeInterface;
use Nelmio\ApiDocBundle\Annotation\Areas;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Areas(['order'])]
#[OA\Tag(name: 'Order')]
class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderFacadeInterface $orderFacade,
    ) {
    }

    #[Route(name: 'create', methods: ['POST'])]
    #[OA\Post]
    #[OA\RequestBody(content: new Model(type: OrderData::class))]
    #[OA\Response(response: Response::HTTP_OK, description: 'Create order')]
    public function create(#[MapRequestPayload]
    OrderData $orderData,): JsonResponse
    {
        return new JsonResponse($this->orderFacade->createOrder($orderData), Response::HTTP_CREATED);
    }

    #[Route('/{id}/expected-delivery-date', name: 'update_delivery_date', methods: ['PATCH'])]
    #[OA\Patch]
    #[OA\RequestBody(content: new Model(type: UpdateOrderDeliveryDateData::class))]
    #[OA\Response(response: Response::HTTP_OK, description: 'Update order delivery date')]
    public function updateDeliveryDate(
        int $id,
        #[MapRequestPayload]
        UpdateOrderDeliveryDateData $updateOrderDeliveryDateData,
    ): JsonResponse {
        return new JsonResponse($this->orderFacade->updateDeliveryDate($id, $updateOrderDeliveryDateData));
    }
}
