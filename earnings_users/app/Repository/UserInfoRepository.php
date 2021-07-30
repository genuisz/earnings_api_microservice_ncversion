<?php 
namespace App\Repository;

use App\Exceptions\AddressBookLimitExceedException;
use App\Repository\Interfaces\UserInfoRepositoryInterface;

class UserInfoRepository extends AbstractRepository implements UserInfoRepositoryInterface  {

    public function model(){
        return 'App\\Models\\UserInfo';
    }
    public function getUserInfo($id,$selectColumn){
        return $this->getOneById($id,$selectColumn);
    }

    public function createUserInfo(array $array){
        return $this->saveDataConstraint($array,null,false);
    }

    public function updateUserInfo(array $array){
        $userId = auth('jwt')->user()->id;
        $userInfo = $this->getOneById($userId);
        $this->setModel($userInfo);
        return $this->updateData($array);
    }

    public function deleteUserInfo(array $array){
        $this->setModel($this->getOneById($array['user_id']));
        return $this->deleteData();
    }

    public function indexUserInfo($order= 'desc', $sort = 'id', $startDate = null, $endDate = null, $offset =0, $limit=15){
        return $this->listAll($order,$sort,$startDate,$endDate,$offset,$limit);
    }
    public function getUserInfoDetails($userId,$countryColumn,$businessNatureColumn,$userInfoColumn){
        $user = $this->getOneById($userId,$userInfoColumn);
        return $user->load($this->userDetailsArrayProducer($countryColumn,$businessNatureColumn));
    }

    public function userDetailsArrayProducer($countryColumn,$businessNatureColumn){
        $array = array();

        if($countryColumn!=null){
            $array=array_merge($array,['country'=>function($q) use ($countryColumn){
                $q->select($countryColumn);
            }]);
        }
        if($businessNatureColumn!=null){
            $array=array_merge($array,['businessNature'=>function($q) use ($businessNatureColumn){
                $q->select($businessNatureColumn);
            }]);
        }


        return $array;

    }

    public function searchUserInfo(){
        
    }
    public function createOrUpdateAddressBook(array $array){
        $array['address_line'] = $array['address_line1'].' '.$array['address_line2'];
        $userId = auth('jwt')->user()->id;
        $this->setModel($this->getOneById($userId));
        $addressBooks = $this->model->addressBook();
        if(is_null($array['address_book_id'])){
            if($addressBooks->count()<=2){
                return $addressBooks->create($array);
            }
            else
            {
                throw new AddressBookLimitExceedException('');
            }
            
        }
        else{
            if($addressBooks->count()<=2){
                $this->deleteAddressBook($array['address_book_id']);
                return $addressBooks->create($array);
            }
            else
            {
                throw new AddressBookLimitExceedException('');
            }

        }
 
    }

    public function deleteAddressBook($addressBookId){
        $userId = auth('jwt')->user()->id;
        $this->setModel($this->getOneById($userId));
        return $this->model->addressBook()->where('id',$addressBookId)->delete();
    }


}