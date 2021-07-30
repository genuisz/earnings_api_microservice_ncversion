<?php
namespace App\Repository\Interfaces;

interface OrderTransactProductRepositoryInterface {
    public function assoicateOrderToProduct($array);
    public function massUpdateDataOfOrderProduct($array);
}