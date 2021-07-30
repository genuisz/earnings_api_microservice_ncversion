<?php
namespace App\Mailable;

use Illuminate\Mail\Mailable;
use App\Models\OrderTransact;
class SendOrderToCustomerMailable extends Mailable{

    public $orderTransact;
    public $logisticColumn;
    public $logisticPortColumn;
    public $orderStatusColumn;
    public $orderProductColumn;
    public function __construct(OrderTransact $orderTransact)
    {
        $this->orderTransact =$orderTransact;
        
    }

    public function setSelectColumn($logisticColumn,$logisticPortColumn,$orderStatusColumn,$orderProductColumn){
        $this->logisticColumn = $logisticColumn;
        $this->logisticPortColumn = $logisticPortColumn;
        $this->orderStatusColumn = $orderStatusColumn;
        $this->orderProductColumn = $orderProductColumn;
    }

    public function build(){
        
        $orderTransactFullData = $this->orderTransact->load([
            'logistic'=>function ($q){
                $q->select($this->logisticColumn);
            },
            'toLogisticPort'=>function($q){
                $q->select($this->logisticPortColumn);
            },
            'orderStatus'=>function($q){
                $q->select($this->orderStatusColumn);
            },
            'orderProduct'=>function($q){
                $q->select($this->orderProductColumn);
            }
        ]); 
        dd($orderTransactFullData);
        $data = [
            'order_transact'=>$orderTransactFullData->orderTransact
        ];

        

         
    }
}