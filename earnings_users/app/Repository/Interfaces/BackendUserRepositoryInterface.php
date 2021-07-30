<?php 
namespace App\Repository\Interfaces;

interface BackendUserRepositoryInterface{

    public function createBackendUser(array $array);

    public function updateBackendUser(array $array);

    public function getBackendUser($id,$userColumn);

    public function deleteBackendUser(array $array);

    public function indexBackendUser($order= 'desc', $sort = 'id', $startDate = null, $endDate = null, $offset =0, $limit=15);

}