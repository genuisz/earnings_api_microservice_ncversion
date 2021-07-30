<?php 
namespace App\Repository\Interfaces;

use App\Http\Requests\AddProductRequest;
use App\Models\Users;
use App\Models\Category;
use App\Models\Factorys as Factory;
use App\Models\ProductStatusType;
use Illuminate\Http\Request;
use App\Repository\AbstractRepository;
use App\Models\Logistic;
use App\Models\OrderStatus;
use Illuminate\Support\Collection;
use App\Models\OrderTransact;
use App\Models\Product;
use phpDocumentor\Reflection\Types\Boolean;

interface OrderTransactRepositoryInterface  {

    public function getOrderTransact( $users,$logisticColumn,$orderStatusColumn,$toPortColumn,$orderTransactColumn, $order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15);

    public function getOrderTransactWithProductDetails($users,$orders,$logisticColumn,$orderStatusColumn ,$productColumn,$quantityUnitColumn,$orderProductStatusColumn,$orderProductColumn,$orderTransactColumn,$toPortColumn,$order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15);

    public function getOrderTransactById($id,$orderColumn);

    public function getOrderTrsanctWithProductDetailsById($id);

    public function getProductDetailByOrderId($productSelectColumn,$orderId,$userId=null);


    public function getByLogistic(Logistic $logistic):OrderTransact;

    public function getByOrderStatus(OrderStatus $orderStatus):OrderTransact;

    // public function getOrderRepo(int $id):self;
    
    public function getOrder( $id):OrderTransact;

    public function getUser():Collection;

    public function getLogistic():Collection;
    
    public function getOrderStatus():Collection;

    public function associateProduct(Product $product,int $quantityOfLog,$logisticCost);
    
    public function listOrderTransactProduct();

    public function createOrder(array $array);

    public function updateOrderTransactStatusToNextStep($orderTransactId);

    public function calculateOrderEachProductFee($item,$product,$unitLogisticCost);

    public function calculateOrderFee($total_fee,$orderFeeArray);

    public function rateOrderProduct($array,$userId);

    public function feedbackOrderProduct($array,$userId);

    public function proceedCalcuateOrderFee($result);

    public function handleCartJsonAddData($cartJson,$params);

    public function getOrderProduct($order);
    
    public function getProduct($order);

    public function loadOrderTransactWithProductDetailsByOrder($order,$logisticColumn,$orderStatusColumn,$productColumn,$quantityUnitColumn,$orderProductStatusColumn,$orderProductColumn,$orderTransactColumn,$fromPortColumn);

    public function updateOrderTransact($array,bool $updateOrderTransact);

    public function deleteOrder($array);

    public function updateOrderTransactProductWithSameAttribute($array,bool $massUpdate);

    public function loadUnitLogisticCost($productList,$toPortId,$logisticId);
    
    public function updateOrderProduct($array);
}