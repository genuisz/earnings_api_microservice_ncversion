<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\HomePageService;
class HomePageController extends Controller
{
    //
    protected $homePageService;
    public function __construct(HomePageService $homePageService)
    {
        $this->homePageService =$homePageService;
    }

    public function listAllCategory(Request $request){
        return $this->homePageService->listAllCategory($request->header('Accept-Language','en'));
    }

    public function listFilter(Request $request){
        $language = $request->header('Accept-Language','en');
        $result =[
            'expire_date' => $this->homePageService->listProductExpiredDateFilter($language),
            'lead_time'=>$this->homePageService->listProductLeadtimeFilter($language),
            'lotsize'=>$this->homePageService->listProductLotsizeFilter($language),
            'achieve_rate'=>$this->homePageService->listProductAchiveveRateFilter($language)
        ];
        return $result;
    }

    public function indexCountry(Request $request){
        $language = $request->header('Accept-Language','en');
        if(!is_null($request['country_id'])){
            return $this->homePageService->getCountry($request['country_id'],$language);
        }
        return $this->homePageService->indexCountry($language,$request['order'],$request['sort'],$request['startDate'],$request['endDate'],$request['offset'],$request['limit'])->get();

       
    }

    public function getBusiness(Request $request){
        $language = $request->header('Accept-Language','en');


        
        return $this->homePageService->getBusiness($request['business_id'],$language);
    }


    // public function getCountry(Request $request){
    //     $language = $request->header('Accept-Language','en');
    //     return $this->homePageService->getCountry($request['country_id'],$language);
    // }

}
