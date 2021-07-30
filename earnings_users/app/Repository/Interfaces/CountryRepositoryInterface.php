<?php
namespace App\Repository\Interfaces;
use Illuminate\Http\Request;
interface CountryRepositoryInterface {
    public function getCountryDetails($language,$requestColumn);

    public function listProduct();
}