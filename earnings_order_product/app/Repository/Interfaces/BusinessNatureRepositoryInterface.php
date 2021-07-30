<?php
namespace App\Repository\Interfaces;

interface BusinessNatureRepositoryInterface {
    public function findBusinessById($id,$selectColumn);
}