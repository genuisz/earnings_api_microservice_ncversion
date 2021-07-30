<?php
namespace App\Repository;

use App\Repository\Interfaces\QuantityUnitRepositoryInterface;

class QuantityUnitRepository extends AbstractRepository implements QuantityUnitRepositoryInterface{

    public function model(){
        return 'App\\Models\\QuantityUnit';
    }
    public function quantityUnit($id,$selectColumn){
        return $this->getOneById($id,$selectColumn);
    }
}