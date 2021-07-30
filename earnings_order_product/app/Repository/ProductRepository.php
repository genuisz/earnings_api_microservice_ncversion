<?php
namespace App\Repository;

use App\Exceptions\AlreadyLikeException;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\InputDataErrorException;
use App\Http\Requests\AddProductRequest;
use App\Models\Category;
use App\Models\Users;
use App\Repository\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\Factorys as Factory;
use App\Models\OptionList;
use App\Models\ProductStatusType;
use App\Exceptions\ProductCreateErrorException;
use App\Exceptions\ProductNumberNotEnoughException;
use App\Models\ProductCommentLike;
use App\Repository\Criteria\StatusActiveCriteria;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class ProductRepository extends AbstractRepository implements ProductRepositoryInterface{
     public function model(){
        return  'App\\Models\\Product';
     }

     public function getById( $id,$productColumn){
         if(is_null($productColumn)){
            return $this->model->where('on_sale',1)->find($id);
         }else{
            return $this->model->where('on_sale',1)->find($id,$productColumn);
         }
         

     }

     public function massUpdateProductQuantity($array){

         try{
            $this->updateDataWithLargeNumber($array);
         }
         catch(Exception $e){
             throw new ProductNumberNotEnoughException($e->getTraceAsString());
         }
         
     }

     public function listProduct($type,$category,$expiredDate,$leadTime,$lotSize,$unitPrice,$achieveRate,$port,$keyword,$productColumn,$categoryColumn,$quantityUnitColumn,$productStatusTypeColumn,$order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15){
        if(empty($keyword)){
            $q = $this->listAll($order,$sort,$startDate,$endDate,$offset,$limit)->where('product_status_type_id',$type);
            if(!empty($category)){
                $q = $q->whereIn('category_id',explode(',',$category));
            }
            if(!empty($expiredDate)){
                $q =$q->whereDate('duedate','<=',$expiredDate);
            }
            if(!empty($leadTime)){
                $q = $this->constraint($leadTime,'leadtime',false,$q);

            }
            if(!empty($lotSize)){
                $q = $this->constraint($lotSize,'quantity_per_log',false,$q);

            }
            if(!empty($unitPrice)){
                $q = $this->constraint($unitPrice,'ROUND(price/quantity_per_log,1) >= ? AND ROUND(price/quantity_per_log,1)<=?',true,$q);
            }

            if(!empty($achieveRate)){
                $q = $this->constraint($achieveRate,'(ROUND(1-(quantity_of_log/quantity_of_log_total),1)) *100 >= ? AND (ROUND(quantity_of_log/quantity_of_log_total,1))*100<=?',true,$q);
            }

            if(!empty($port)){
                $q = $this->constraint($port,'from_port_id = ? ',true,$q);
            }
                $product =$q->select($productColumn)->get();
            
            
        }
        else{
            $product = $this->searchAll($keyword,$order,$sort,$startDate,$endDate,$offset,$limit)->select($productColumn)->get();
        }
        
        $result = $this->productLoadWithCategoryAndProduct($product,$categoryColumn,$quantityUnitColumn,$productStatusTypeColumn);

        $result->map(function(Product $product){
            $product->makeHiddenIf($product->product_status_type_id==2,array('quantity_of_log','quantity_per_log','quantity_reach_target_in_log','price'));
            return $product;
        });

        return $result;
     }
     public function constraint($column,$sqlColumn,$raw,$q){
        $column = collect(explode(',',$column));
        $column = $column->map(function($item){
            return explode('-',$item);
        });                
        foreach($column as $index => $element){
            if($index==0){
                if($raw==true){
                    $q = $q->whereRaw($sqlColumn,$element);
                    //$q = $q->whereRaw('ROUND(price/quantity_per_log,1) >= ? AND ROUND(price/quantity_per_log,1)<=?',$price);
                }
                else{
                    $q = $q->whereBetween($sqlColumn,$element);
                }
                
            }
            else{
                if($raw==true){
                    $q = $q->orWhereRaw($sqlColumn,$element);
                }
                else{
                    $q = $q->orWhereBetween($sqlColumn,$element);
                }
                
            }
           
        }

        return $q;
     }

 




     public function productLoadWithCategoryAndProduct($product,$categoryColumn,$quantityUnitColumn,$productStatusTypeColumn){
        $result =  $product->load([
            'category'=>function($q) use ($categoryColumn){
                $q->select($categoryColumn);

            },
            'quantityUnit'=>function($q) use($quantityUnitColumn){
                $q->select($quantityUnitColumn);
            },
            'productStatusType'=>function($q) use ($productStatusTypeColumn){
                $q->select($productStatusTypeColumn);
            }
            
        ]);
        return $result;
     }

     public function productLoadUnitOrderProductStatus($product,$orderTransactId,$quantityUnitColumn,$orderProductColumn,$orderProductStatusColumn){
        $orderTransactProductDetails = $product->load(
            [
                'quantityUnit'=>function($q) use ($quantityUnitColumn){
                    $q->select($quantityUnitColumn);
                },
                'orderProduct'=>function($q)use ($orderProductColumn,$orderTransactId){
                    $q->where('order_transact_id',$orderTransactId)->select($orderProductColumn);
                },
                'orderProduct.orderStatus'=>function($q)use($orderProductStatusColumn){
                    $q->select($orderProductStatusColumn);

                }
        
            ]);
            return $orderTransactProductDetails;
     }

     



     public function productLoadProductDetails($product,$categoryColumn,$quantityUnitColumn,$productStatusTypeColumn,$factoryColumn,$countryColumn,$fromPortColumn,$fromPortCountryColumn,$fromPortTypeColumn){
         return $product->load([
             'category'=>function($q) use($categoryColumn){
                $q->select($categoryColumn);
             },
             'quantityUnit'=>function($q) use ($quantityUnitColumn){
                $q->select($quantityUnitColumn);
             },
             'productStatusType'=>function($q) use ($productStatusTypeColumn){
                $q->select($productStatusTypeColumn);
             },
             'factorys'=>function($q) use ($factoryColumn){
                $q->select($factoryColumn);
             },
             'factorys.country'=>function($q) use ($countryColumn){
                 $q->select($countryColumn);
             },
             'fromLogisticPort'=>function($q) use ($fromPortColumn){
                 $q->select($fromPortColumn);
             },
             'fromLogisticPort.country'=>function($q) use ($fromPortCountryColumn){
                 $q->select($fromPortCountryColumn);
             },
             'fromLogisticPort.portType'=>function($q) use ($fromPortTypeColumn){
                 $q->select($fromPortTypeColumn);
             }
         ]);


     }



     public function likeCommentProduct(array $array){
        $product = $this->getOneById($array['product_id']);
        if(count($product->productCommentLike()->where('users_id',$array['users_id'])->get())>0){
            throw new AlreadyLikeException("");
        }
        $commentLike = new ProductCommentLike($array);
        $commentLike->product_id = $array['product_id'];
        $commentLike->users_id =$array['users_id'];
        //$commentLike->users_id =auth('jwt')->user()->id;
        return $product->productCommentLike()->save($commentLike);
     }

     public function getFactorys():Collection{
         return $this->model->factorys()->get();
     }
     public function getByCategory( $category,$offset=10,$orderBy = 'duedate desc')
     {
         return $this->model->where('category_id',$category->id)->limit($offset)->orderByRaw($orderBy);
     }
     public function getByFactory(Factory $factory)
     {
         return $this->model->where('factory_id',$factory->id)->get();
     }

     public function getByProductStatusType(ProductStatusType $productStatusType)
     {
         return $this->model->where('product_status_type_id',$productStatusType->id)->get();
     }

     public function storeProduct(array $array,bool $massInsert){

         
            try{
                if($massInsert){
                    foreach($array as $index=>$value){
                        $array[$index]['product_no'] =quickRandom(10);
                    }
                    return $this->createDataWithLargeNumber($array);
                }
                else{
                    $array['product_no'] = quickRandom(10);
                    return $this->saveData($array,false);
                }
            }
            catch(Exception $e){
                throw new DataNotFoundException($e->getTraceAsString());
            }



     }

     public function updateProduct($array,bool $massUpdate){
        if($massUpdate){
            $data = $this->model->getFillable();
            array_push($data,$this->model->getKeyName());
            if(jsonParaChecker($array,$data,true)){
                return $this->updateDataWithLargeNumber($array);
            }
            else{
                throw new InputDataErrorException("");
            }
            
        }
        else{
            
            return $this->updateData($array);
        }

     }


    //  public function testCriteria(){
    //      $this->pushCriteria(new StatusActiveCriteria());
    //  }






     

}