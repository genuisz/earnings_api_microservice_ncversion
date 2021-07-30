<?php
namespace App\Repository\Interfaces;

interface CategoryRepositoryInterface{

    public function listCategory($categoryColumn,$order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15);
    
    public function listCategoryInLV2($categoryColumn,$order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15);

}