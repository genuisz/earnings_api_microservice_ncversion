<?php
namespace App\Repository;

use App\Repository\Interfaces\BusinessNatureRepositoryInterface;

class BusinessNatureRepository extends AbstractRepository implements BusinessNatureRepositoryInterface {

    public function model()
    {
        return 'App\\Models\\BusinessNature';
    }

    public function findBusinessById($id,$selectColumn){
        return $this->getOneById($id,$selectColumn);
    }
}