<?php 
namespace App\Repository\Interfaces;

interface OrderStatusRepositoryInterface {
    public function orderStatus($id,$selectOrderStatusColumn);
}
