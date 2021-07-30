<?php

namespace App\Repository\Interfaces;

interface UserInfoRepositoryInterface{

    public function getUserInfo($id,$selectColumn);

    public function createUserInfo(array $array);

    public function updateUserInfo(array $array);

    public function deleteUserInfo(array $array);

    public function indexUserInfo($order= 'desc', $sort = 'id', $startDate = null, $endDate = null, $offset =0, $limit=15);

    public function createOrUpdateAddressBook(array $array);

    public function deleteAddressBook($id);

    public function getUserInfoDetails($userId,$countryColumn,$businessNatureColumn,$userInfoColumn);


}