<?php 
namespace App\Repository\Interfaces;

use App\Http\Requests\AddProductRequest;
use App\Models\Users;
use App\Models\Category;
use App\Models\Factorys as Factory;
use App\Models\ProductStatusType;
use Illuminate\Http\Request;
use App\Repository\AbstractRepository;
use Illuminate\Support\Collection;
interface ProductRepositoryInterface  {

    public function getByCategory(Category $category);

    public function getByFactory(Factory $factory);

    public function getByProductStatusType(ProductStatusType $productStatusType);
    
    public function getById( $id,$productColumn);

    public function storeProduct(array $array,bool $massInsert);

    public function getFactorys():Collection;

    public function listProduct($type,$category,$expiredDate,$leadTime,$lotSize,$unitPrice,$archieveRate,$port,$keyword,$productColumn,$categoryColumn,$quantityUnitColumn,$productStatusTypeColumn,$order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15);

    public function productLoadProductDetails($product,$categoryColumn,$quantityUnitColumn,$productStatusTypeColumn,$factoryColumn,$countryColumn,$fromPortColumn,$fromPortCountryColumn,$fromPortTypeColumn);


    public function likeCommentProduct(array $array);

    public function productLoadUnitOrderProductStatus($product,$orderTransactId,$quantityUnitColumn,$orderProductColumn,$orderProductStatusColumn);
    
    public function updateProduct($array,bool $massUpdate);
    //public function searchProduct($keyword,$order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15);
}