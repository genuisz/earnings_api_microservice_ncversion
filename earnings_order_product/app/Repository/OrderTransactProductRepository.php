<?php
namespace App\Repository;

use App\Repository\Interfaces\OrderTransactProductRepositoryInterface;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
class OrderTransactProductRepository extends AbstractRepository implements OrderTransactProductRepositoryInterface{

    public function model(){
        return 'App\\Models\\OrderTransactProduct';
    }
    public function assoicateOrderToProduct($array){
        $this->createDataWithLargeNumber($array,false);
    }

    public function massUpdateDataOfOrderProduct($array){
        

            $this->updateDataWithLargeNumber($array,['order_transact_id','product_id']);

    }


}