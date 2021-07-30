<?php
namespace App\Service;

use App\Repository\Interfaces\BusinessNatureRepositoryInterface;
use App\Repository\Interfaces\CategoryRepositoryInterface;
use App\Repository\Interfaces\CountryRepositoryInterface;
use App\Repository\Interfaces\ProgramSettingRepositoryInterface;
use App\Repository\ProgramSettingRepository;

class HomePageService{
    protected $categoryRepo;
    protected $programSettingRepo;
    protected $countryRepo;
    protected $businessRepo;
    public function __construct(CategoryRepositoryInterface $categoryRepo, ProgramSettingRepositoryInterface $programSettingRepo,CountryRepositoryInterface $countryRepo,BusinessNatureRepositoryInterface $businessRepo)
    {   
        $this->categoryRepo = $categoryRepo;
        $this->programSettingRepo = $programSettingRepo;
        $this->countryRepo = $countryRepo;
        $this->businessRepo = $businessRepo;
    }

    public function listAllCategory($language){
        return $this->categoryRepo->listCategory(['id','name_'.$language,'parent_id'],'id','desc',null,null,0,100);
    }

    public function listProductExpiredDateFilter($language){
        return $this->programSettingRepo->getProductFilterInfo('product_filter','expire_date',$language);
    }
    public function listProductLeadtimeFilter($language){
        return $this->programSettingRepo->getProductFilterInfo('product_filter','leadtime',$language);
    }
    public function listProductLotsizeFilter($language){
        return $this->programSettingRepo->getProductFilterInfo('product_filter','lotsize',$language);
    }
    public function listProductAchiveveRateFilter($language){
        return $this->programSettingRepo->getProductFilterInfo('product_filter','achieve_rate',$language);
    }

    public function indexCountry($language,$order,$sort,$startDate,$endDate,$offset,$limit){
        return $this->countryRepo->indexCountry(['id','name_'.$language],$order,$sort,$startDate,$endDate,$offset,$limit);
    }

    public function getCountry($countryId,$language){
        return $this->countryRepo->getCountryDetail($countryId,['id','name_'.$language]);
    }
    public function getBusiness($businessId,$language){
        return $this->businessRepo->findBusinessById($businessId,['id','name_'.$language]);
    }



}