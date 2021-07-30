<?php
namespace App\Repository\Interfaces;
use Illuminate\Http\Request;
interface BusinessNatureRepositoryInterface {
    public function getBusiness($businessId,$language);

    public function listProduct();
}