<?php
namespace App\Repository\Interfaces;

interface ProgramSettingRepositoryInterface {

    public function getProductFilterInfo($type,$key,$language);
}