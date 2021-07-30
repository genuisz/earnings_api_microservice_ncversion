<?php
namespace App\Repository\Interfaces;
interface CartItemRepositoryInterface {  
    public function createCartItem(array $array);
    // public function updateCartItem(array $array,bool $massupdate);
    public function getCartItems($cart);
    public function comparingCartItemInCart($cart,$array);
    public function removeCartItems($carts);
    public function loadCartItemProductDetails($cartItems,$productColumn,$cartItemColumn);
    public function updateCartItemInCart($cart,$array,bool $massupdate);
    
}