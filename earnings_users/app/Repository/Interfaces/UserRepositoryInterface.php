<?php 
namespace App\Repository\Interfaces;

use App\Repository\UsersRepository;

interface UserRepositoryInterface{
    public function createUser(array $array);

    public function createUserInfo(array $array);

    public function updateUser(array $array);

    public function getUser($id,$userColumn);

    public function deleteUser(array $array);

    public function indexUser($order= 'desc', $sort = 'id', $startDate = null, $endDate = null, $offset =0, $limit=15);

    public function getUserInfoDetails($id,$query);

    public function getUserInfo();

    public function verifyUser($id);

    public function forgotPassword($array);

    public function generateVerificationURL($user);

    public function resetPassword($array);
    
    public function firstStepResetPassword($array);

}