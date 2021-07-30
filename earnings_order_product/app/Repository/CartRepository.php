<?php
namespace App\Repository;

use App\Repository\Interfaces\CartRepositoryInterface;
use Carbon\Carbon;
use App\Models\Cart;
class CartRepository extends AbstractRepository implements CartRepositoryInterface{
    public function model(){
        return 'App\\Models\\Cart';
    }
    public function createCart(array $array){
        $array['duedate']= Carbon::now()->addDays(3)->toDateTimeString();
        //return $this->saveDataConstraint($array,null,false);
        return $this->saveData($array,false);
        
    }

    public function findRecentCart($userId){
        return $this->findAllCart($userId)->whereDate('duedate','>=',Carbon::now()->toDateTimeString())->orderBy('duedate','desc')->first();

    }
    
    public function findAllCart($userId){
        return $this->model->where('users_id',$userId);
    }

    public function removeCarts($userId){
        $carts = $this->findAllCart($userId);
        $carts->delete();
    }



    public function getCorrespondingCartItems($cart){
        return $this->getOneById($cart['id'])->cartItem;
    }

    public function findExistingCartItemInCart($cart){
        $cartItems = $this->getCorrespondingCartItems($cart);

        
    }
    // public function updateOrInsertCorrespondingCartItems($cart,$dataArray,$uniqueColumn,$updateColumn){

    //     $this->setModel($cart);
    //     return $this->model->cartItem()->upsert($dataArray,$uniqueColumn,$updateColumn);
    // }

    
}