<?php
namespace App\Repository;

use App\Repository\Interfaces\CountryRepositoryInterface;

class CountryRepository extends AbstractRepository implements CountryRepositoryInterface {

    public function model(){
        return 'App\Models\Country';
    }

    public function indexCountry($countryColumn,$order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15){ 
        //  $args_list = func_get_args();

        //  $func = call_user_func_array(array($this,'listAll'),$args_list) ;
        //  return $func->select($countryColumn);
        return $this->listAll($order,$sort,$startDate,$endDate,$offset,$limit)->select($countryColumn);
    }
    
    public function getCountryDetail($countryId,$countryColumn){
        return $this->getOneById($countryId,$countryColumn);
    }
}