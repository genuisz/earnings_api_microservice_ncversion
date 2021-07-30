<?php
namespace App\Repository;

use App\Exceptions\CreateOrUpdateCartException;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\InputDataErrorException;
use App\Exceptions\UpdateDataErrorException;
use App\Models\CartItem;
use App\Repository\Interfaces\CartItemRepositoryInterface;
use Exception;

class CartItemRepository extends AbstractRepository implements CartItemRepositoryInterface{
    public function model(){
        return 'App\\Models\CartItem';
    }
    public function createCartItem(array $array){
        //dd($array);
        return $this->createDataWithLargeNumber($array);
    }
    public function updateCartItem(array $array,bool $massupdate){
        if($massupdate){
            return $this->updateDataWithLargeNumber($array);
        }
        else{
            if(array_key_exists('cart_id',$array) && array_key_exists('product_id',$array)){
                $cartItem = $this->model->where('cart_id',$array['cart_id'])->where('product_id',$array['product_id'])->first();
                $this->setModel($cartItem);
            }

            $this->updateData($array);
        }

    }

    public function getCartItems($cart){
        
    }
    public function removeCartItems($carts){
        $q = $this->model;
        foreach($carts->get() as $index =>$cart){
            
            if($index==0){
                $q = $q->where('cart_id',$cart['id']);
            }
            else{
                $q =$q->orWhere('cart_id',$cart['id']);
            }
        }
        return $q->delete();
    }

    public function comparingCartItemInCart($cartItems,$array){
        
        try{
           
            $cartItems = array_column($cartItems->toArray(),null,'product_id');
            
            $createArray = array();
            $updateArray = array();
            foreach($array as $element){
                if(array_key_exists($element['product_id'],$cartItems)){
                    $element['quantity_of_log'] = $element['quantity_of_log']+$cartItems[$element['product_id']]['quantity_of_log'];
                    $element['id'] = $cartItems[$element['product_id']]['id'];
                    array_push($updateArray,$element);
                    
                }
                else{
    
                    array_push($createArray,$element);
                }
            }
        }
        catch(Exception $e){
            throw new InputDataErrorException($e->getTraceAsString());
        }

        try{
            if(!empty($createArray)){
                $result = $this->createCartItem($createArray);

            }

            if(!empty($updateArray)){
                //dd($updateArray);
                $result = $this->updateCartItem($updateArray,true);

            }
        }
        catch(Exception $e){
            throw new CreateOrUpdateCartException($e->getTraceAsString());
        }

        
        return $result;


    }

    public function updateCartItemInCart($cart,$array,bool $massupdate){
        if($massupdate==false){
            try{
                $filter = ['cart_item_id'];
                $allow = array_filter($array,function($key) use ($filter){
                    return !in_array($key,$filter);
                },ARRAY_FILTER_USE_KEY);

                return $cart->cartItem()->where('id',$array['cart_item_id'])->update($allow);
            }
            catch(Exception $e){
                throw new UpdateDataErrorException($e->getTraceAsString());
            }

        }
        else{
        
            $data = [];

            $existCartItem  =$cart->cartItem->toArray();
            foreach( $existCartItem as $index=>$value){
                if(array_key_exists('id',$value)){
                    $data[$value['id']] = true;
                }
            }
    
    
            $array= collect($array)->map(function($value,$key)use ($data){
               
                $value['id'] = $value['cart_item_id'];
                unset($value['cart_item_id']);
                
                return $value;
            });
    
            $array = array_values($array->filter(function($value,$key) use ($data){
                return array_key_exists($value['id'],$data);
            })->toArray());
            if(count($array)>0){
                return $this->updateDataWithLargeNumber($array);
            }
            else{
                throw new DataNotFoundException("");
            }
            
        }

        /**
         * Frist we find out the corresponding cart items of the user
         * only the correspondings items can be updated with the corresponding user and non-expired items
         */
        


        
        
       
        
       
    }


    public function loadCartItemProductDetails($cartItems,$productColumn,$cartItemColumn){
        // dd($cartItems->select($cartItemColumn)->get());
        return $cartItems->select($cartItemColumn)->get()->load([
            'product'=>function($q) use ($productColumn){
                $q->select($productColumn);
            }
        ]);
    }
    
}