<?php
namespace App\Repository;

use App\Models\Category;
use App\Repository\Interfaces\CategoryRepositoryInterface;

class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface{

    public function model(){
        return 'App\\Models\\Category';
    }

    public function listCategory($categoryColumn,$order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15){
         $category = $this->listAll($order,$sort,$startDate,$endDate,$offset,$limit)->whereNull('parent_id')->get();
         $result =  $category->load('hasChildCategory');
         return $this->recursionMapCategoryColumn($result,$categoryColumn);

    }
    public function recursionMapCategoryColumn($categorys,$categoryColumn){
            return $categorys->map(function(Category $category) use ($categoryColumn){
                if($category['hasChildCategory']->isEmpty()){
                    $category->makeHidden(array_diff(array_keys($category->getAttributes()),$categoryColumn));
                    return $category;
                }
                else{              
                    $this->recursionMapCategoryColumn($category['hasChildCategory'],$categoryColumn);
                    $category->makeHidden(array_diff(array_keys($category->getAttributes()),$categoryColumn));
                    return $category;
                }
            });
    }
    public function listCategoryInLV2($categoryColumn,$order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15){
        $category = $this->listAll($order,$sort,$startDate,$endDate,$offset,$limit)->whereNull('parent_id')->get();
        $result = $category->load(['hasChildCategory.hasChildCategory'=>function($q)use ($categoryColumn){
            $q->select($categoryColumn);
        }]);
        return $result;
    }





        

        
    }





