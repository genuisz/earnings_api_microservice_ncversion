<?php 
namespace App\Traits;

use App\Models\OrderTransact;
use App\Models\OrderTransactProduct;
use App\Models\Product;
use App\Repository\OrderStatusRepository;
use App\Repository\QuantityUnitRepository;

trait OrderProductTransformable
{
    /**
     * Transform the order
     *
     * @param Order $order
     * @return Order
     */
    protected function transformOrderWithProductDetails(Product $product) : Product
    {
        // $product->order_products = $orderProduct->toArray();
        $product->unique_order_product_id = $product->order_product->unique_order_product_id;
        $product->order_quantity_in_log = $product->order_product->order_quantity_in_log;
        $product->confirmed_delivery_date = $product->order_product->confirmed_delivery_date;
        $product->expected_ETD = $product->order_product->expected_ETD;
        $product->due_at = $product->order_product->due_at;
        $product->downpayment = $product->order_product->downpayment;
        $product->deposit =$product->order_product->deposit;
        $product->total = $product->order_product->total;
        $product->feedback =$product->order_product->feedback;

        return $product;
    }
}