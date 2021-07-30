<?php 

namespace App\Repository;

use App\Repository\Interfaces\ProgramSettingRepositoryInterface;

class ProgramSettingRepository extends AbstractRepository implements ProgramSettingRepositoryInterface{

    public function model(){
        return 'App\\Models\ProgramSetting';
    }
    public function getProductFilterInfo($type,$key,$language){
        return $this->model->where('type',$type)->where('key',$key)->select(['id','type','key','value','name_'.$language])->get()->toArray();
    }
}