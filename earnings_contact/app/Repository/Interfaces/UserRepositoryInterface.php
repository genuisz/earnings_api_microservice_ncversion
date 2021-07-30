<?php 
namespace App\Repository\Interfaces;

use App\Repository\UsersRepository;

interface UserRepositoryInterface{
    public function verifyUser($id);

}