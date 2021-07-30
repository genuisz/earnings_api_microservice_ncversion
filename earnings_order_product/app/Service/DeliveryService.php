<?php
namespace App\Service;

use App\Repository\Interfaces\OrderTransactRepositoryInterface;
use App\Repository\Interfaces\ProductRepositoryInterface;

class DeliveryService {
    protected $orderTransactRepo;
    protected $productRepo;
    public function __construct(OrderTransactRepositoryInterface $orderTransactRepo , ProductRepositoryInterface $productRepo )
    {
        $this->orderTransactRepo = $orderTransactRepo;
        $this->productRepo =$productRepo;
    }

}