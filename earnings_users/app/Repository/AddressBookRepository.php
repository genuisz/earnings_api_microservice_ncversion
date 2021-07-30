<?php
namespace App\Repository;
use App\Repository\Interfaces\AddressBookRepositoryInterface;
class AddressBookRepository extends AbstractRepository implements AddressBookRepositoryInterface{

    public function model()
    {
        return 'App\\Models\\AddressBook';
    }
    
    public function getAddressBook($addressBookId,$selectAddressColumn){
        return $this->getOneById($addressBookId,$selectAddressColumn);
    }

    public function updateAddressBook($array,bool $mass){
        if($mass){
            return $this->updateDataWithLargeNumber($array);
        }

        $array['address_line'] = $array['address_line1'].' '.$array['address_line2'];
        return $this->getOneById($array['address_book_id'])->update($array);
    }

    public function indexAddressBook($selectAddressColumn,$order= 'desc', $sort = 'id', $startDate = null, $endDate = null, $offset =0, $limit=15){

    }

    public function deleteAddressBook($array,bool $mass){
        if($mass){

        }
        else{
            $this->setModel($this->getOneById($array['address_book_id']));
            $this->model->delete();
        }
    }
    
    public function createAddressBook($array,bool $mass){
        if($mass){
            return $this->createDataWithLargeNumber($array);
        }
        
        return $this->saveData($array,false);
        
        
        
        
        
    }

}