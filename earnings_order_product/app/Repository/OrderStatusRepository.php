<?php 
namespace App\Repository;

use App\Repository\Interfaces\OrderStatusRepositoryInterface;

class OrderStatusRepository extends AbstractRepository implements OrderStatusRepositoryInterface {

    public function model(){
        return 'App\\Models\\OrderStatus';
    }

    public function orderStatus($id,$selectOrderStatusColumn)
    {
        return $this->getOneById($id,$selectOrderStatusColumn);
    }


}