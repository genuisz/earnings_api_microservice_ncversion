<?php
namespace App\Service;

use App\Exceptions\InputDataErrorException;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Repository\Interfaces\CategoryRepositoryInterface;
use App\Repository\Interfaces\ProductRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductService {
    protected $productRepo;
    protected $categoryRepo;
    public function __construct(ProductRepositoryInterface $productRepo,CategoryRepositoryInterface $categoryRepo)
    {
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function getProductList($type,$category,$expiredDate,$leadTime,$lotSize,$unitPrice,$achieveRate,$port,$keyword,$order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15,$language='en'){
        
        return $this->productRepo->listProduct(
            $type,
            $category,
            $expiredDate,
            $leadTime,
            $lotSize,
            $unitPrice,
            $achieveRate,
            $port,
            $keyword,
            ['id','category_id','factory_id','name_'.$language,'product_no','quantity_per_log','quantity_unit_id',DB::raw('CAST( (quantity_of_log_inventory_limit-quantity_of_log)/quantity_of_log_total *100 as DECIMAL(10,2)) as reach_rate'),'price','duedate','image_url','product_status_type_id',DB::raw('CAST(price / quantity_per_log as DECIMAL(10,2)) as average_unit_price')],
            ['id','name_'.$language],
            ['id','name_'.$language],
            ['id','name_'.$language],
            $order,
            $sort,
            $startDate,
            $endDate,
            $offset,
            $limit
        );
    }

    public function getProductDetails(Request $request){
        $language = $request->header('Accept-Language','en');
        $product = $this->productRepo->getById($request['product_id'],['id','category_id','factory_id','name_'.$language,'product_no','description','quantity_per_log','quantity_unit_id',DB::raw('CAST( (quantity_of_log_inventory_limit-quantity_of_log)/quantity_of_log_total *100 as DECIMAL(10,2)) as reach_rate'),'price','product_status_type_id','from_port_id','downpayment_ratio','deposit_ratio','duedate','image_url']);
        return $this->productRepo->productLoadProductDetails(
        $product,
        ['id','name_'.$language],
        ['id','name_'.$language],
        ['id','name_'.$language],
        ['id','country_id','name_'.$language],
        ['id','name_'.$language],
        ['id','country_id','port_type_id','name_'.$language],
        ['id','name_'.$language],
        ['id','name_'.$language]
        );

    }

    public function likeProduct(Request $request){
        $userId  = auth('jwt')->user()->id;
        $array = $request->only(['product_id','comment','type']);
        $array['users_id'] = $userId;

        return $this->productRepo->likeCommentProduct($array);
    }

    public function insertProduct(Request $request){
        if($request->has('product_json')){
            try{
                $json = json_decode($request['product_json'],true);
            }
            catch(Exception $e){
                throw new InputDataErrorException($e->getTraceAsString());
            }
            
            return $this->productRepo->storeProduct($json,true);
        }
        else{
            return $this->productRepo->storeProduct($request->except('image'),false);
        }
        
       
    }

    public function updateProduct(UpdateProductRequest $request){
        if($request->has('product_json')){
            try{
                $json = json_decode($request['product_json'],true);
            }
            
            catch(Exception $e){
                throw new InputDataErrorException($e->getTraceAsString());
            }
            return $this->productRepo->updateProduct($json,true);
        }
        else{
            return $this->productRepo->updateProduct($request->all(),false);
        }
        
    }






    






}