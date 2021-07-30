<?php
namespace App\Repository\Interfaces;

interface AddressBookRepositoryInterface {
    public function getAddressBook($addressBookId,$selectAddressColumn);

    public function updateAddressBook($array,bool $mass);

    public function indexAddressBook($selectAddressColumn,$order= 'desc', $sort = 'id', $startDate = null, $endDate = null, $offset =0, $limit=15);

    public function deleteAddressBook($array, bool $mass);

    public function createAddressBook($array,bool $mass);

}