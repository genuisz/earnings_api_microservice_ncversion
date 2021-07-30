<?php 
namespace App\Repository;

use App\Models\Role;
use App\Repository\Interfaces\BackendUserRepositoryInterface;
class BackendUserRepository extends AbstractRepository implements BackendUserRepositoryInterface{

    public function model(){
        return 'App\Models\BackendUser';
    }
    public function test(){
        $user = $this->getOneById(1);
        
        // $role  = new Role();
        dd($user->permissions);
        $role = $user->role();
        $roleList = $role->get();
        foreach($roleList as $roleE){ 
            $test []= $roleE->permission()->get()->toArray();
        }
        dd($test);

        
        
    }
}