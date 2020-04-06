<?php

// command bus
class OrderService{
    //command handler
    private CancelOrderService $cancelOrderService;
    //command handler
    private CreateOrderService $createOrderService;

    public function __construct(
        CancelOrderService $cancelOrderService,
        CreateOrderService $createOrderService
    )
    {
        $this->cancelOrderService = $cancelOrderService;
        $this->createOrderService = $createOrderService;
    }

    //handling command from dto like simple Command object
    public function createOrder(CreateOrderDto $command):OrderDTO
    {
        $this->createOrderService->createOrder($command);
        //...
    }

    //handling command from base types
    public function cancelOrder(Uuid $id, string $reason):void
    {
        $this->cancelOrderService->cancelOrder($id,  $reason);
        //...
    }
}
class CancelOrderService{
//    ...
}
class CreateOrderService{
//    ...
}