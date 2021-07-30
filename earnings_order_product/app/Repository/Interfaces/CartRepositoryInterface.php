<?php
namespace App\Repository\Interfaces;

interface CartRepositoryInterface{
    public function createCart(array $array);
    public function findRecentCart($userId);
    public function getCorrespondingCartItems($cart);
    public function findExistingCartItemInCart($cart);
    public function removeCarts($userId);
    public function findAllCart($userId);
    //public function updateOrInsertCorrespondingCartItems($cart,$dataArray,$uniqueColumn,$updateColumn);
}