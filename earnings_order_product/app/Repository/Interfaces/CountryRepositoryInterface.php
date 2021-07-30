<?php
namespace App\Repository\Interfaces;
interface CountryRepositoryInterface {
    public function indexCountry($countryColumn,$order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15);
    
    public function getCountryDetail($countryId,$countryColumn);
}