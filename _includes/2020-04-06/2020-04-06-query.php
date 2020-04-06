<?php

// command bus
class OrderService
{
    //command handler
    private CancelOrderService $cancelOrderService;
    private CreateOrderService $createOrderService;
    //query bus
    private OrderReadRepository $queryRepository;

    public function __construct(
        CancelOrderService $cancelOrderService,
        CreateOrderService $createOrderService,
        OrderReadRepository $queryRepository
    )
    {
        $this->cancelOrderService = $cancelOrderService;
        $this->createOrderService = $createOrderService;
        $this->queryRepository = $queryRepository;
    }

    //handling command from dto like simple Command object
    public function createOrder(CreateOrderDto $command): OrderDTO
    {
        $this->createOrderService->createOrder($command);
        return $this->query()->getOrder($command->getUuid());
    }

    //handling command from base types
    public function query(): OrderReadRepository
    {
        return $this->queryRepository;
    }
}

class CancelOrderService
{
    private OrderRepository $orderRepository;

    private OrderReadRepository $orderQueryRepository;

    public function __construct(OrderRepository $orderRepository, OrderReadRepository $orderQueryRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->orderQueryRepository = $orderQueryRepository;
    }

//    ...
}

interface OrderRepository
{
}

interface OrderReadRepository
{
    public function getOrder(Uuid $id): OrderDTO;

    public function getOrderDetails(Uuid $id): OrderDetailsDTO;
}