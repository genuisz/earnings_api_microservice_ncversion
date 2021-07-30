<?php 
namespace App\Service;

use App\Event\RegisteredEvent;
use App\Exceptions\DeleteUserException;
use App\Exceptions\DuplicatedUserException;
use App\Exceptions\PermissionNotEnoughException;
use App\Http\Requests\AddAddressBookRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\DeleteAddressBookRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Repository\Interfaces\AddressBookRepositoryInterface;
use App\Repository\Interfaces\BusinessNatureRepositoryInterface;
use App\Repository\Interfaces\CountryRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\Interfaces\UserInfoRepositoryInterface;
use App\Traits\ApiRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserService {

    use ApiRequest;
    protected $userRepository;
    protected $userInfoRepository;
    protected $countryRepo;
    protected $businessRepo;
    protected $addressRepo;
    public function __construct(UserRepositoryInterface $userRepository,UserInfoRepositoryInterface $userInfoRepository,CountryRepositoryInterface $countryRepo,BusinessNatureRepositoryInterface $businessRepo,AddressBookRepositoryInterface $addressRepo)
    {
        $this->userRepository = $userRepository;
        $this->userInfoRepository = $userInfoRepository;
        $this->countryRepo = $countryRepo;
        $this->businessRepo = $businessRepo;
        $this->addressRepo = $addressRepo;
        $this->baseUri = env('SERVICE_ORDER_BASE_URL');
        $this->secret = "";
    }

    public function createUser(RegisterRequest $request){
        $request['registered_ip'] = $request->ip();
        $request['recent_ip'] = $request->ip();
        DB::beginTransaction();
        try{
            $user=$this->userRepository->createUser($request->only(['email','username','password']));
            $result =  $this->userRepository->createUserInfo($request->only(['name','contact_no','registered_ip','recent_ip','country_id','business_nature_id','interested_category','company_website','company_address','gender']));   
        }
        catch(Exception $e){
            DB::rollBack();
            //echo $e->getTraceAsString();
            throw new DuplicatedUserException($e->getTraceAsString());
        }
        DB::commit();
        $data = $this->userRepository->generateVerificationURL($user);
        event(new RegisteredEvent($data));
        return $result;
  
    }


    public function updateUserInfo(UpdateUserInfoRequest $request){
        return $this->userInfoRepository->updateUserInfo($request->only(['name','gender','reward_point','country_id','business_nature_id','interested_category','company_address','contact_no','notification_status','registed_ip','recent_ip']));
    }

    public function deleteUser(Request $request){
        DB::beginTransaction();
        try{
            $this->userRepository->deleteUser($request->only(['user_id']));
            $this->userInfoRepository->deleteUserInfo($request->only(['user_id']));
        }
        catch(Exception $e){
            DB::rollBack();
            throw new DeleteUserException($e->getTraceAsString());
        }
        DB::commit();

        return [
            'status'=>'Y'
        ];

    }
    
    public function getUserById($id){
        return $this->userRepository->getUserInfo($id,['id','name'])->get(['id','email','username']);
    }

    public function getUserInfo($id,$language){
        $userInfo = $this->userInfoRepository->getUserInfo($id,['*']);
        
        $promise['country'] =  $this->countryRepo->getCountryDetails($language,$userInfo->country_id);
        $promise['business'] = $this->businessRepo->getBusiness($userInfo->business_nature_id,$language);

        $response = $this->performAsyncRequest($promise,false);
        $userInfo['country'] = json_decode($response['country']['value']->getBody()->getContents());
        $userInfo['business']= json_decode($response['business']['value']->getBody()->getContents());
        

        return [
            'status'=>'Y',
            'user_info'=>$userInfo
        ];

        
        

    }

    public function updateUserPassword(ChangePasswordRequest $request){
        $request['password'] = Hash::make($request['new_password']);
        $this->userRepository->updateUser($request->only('password'));
        return [
            'status'=>'Y'
        ];
        

    }

    public function forgotPassword(Request $request){
        $this->userRepository->forgotPassword($request->all());
    }

    public function createOrUpdateAddressBook(AddAddressBookRequest $request){

            if($request->user()->isAllowPermission('edit-user-address')){
                return $this->addressRepo->updateAddressBook($request->all(),false);
            }
            else{
                throw new PermissionNotEnoughException("");
            }
        
        return $this->userInfoRepository->createOrUpdateAddressBook($request->all());
    }

    







    public function deleteAddressBook(DeleteAddressBookRequest $request){

            if($request->user()->isAllowPermission('edit-user-address')){
                return $this->addressRepo->deleteAddressBook($request->all(),false);
            }
            else{
                throw new PermissionNotEnoughException("");
            }
        
        return $this->userInfoRepository->deleteAddressBook($request->only('address_book_id'));
    }
    
    public function verifyUser($id){
        return $this->userRepository->verifyUser($id);
    }

    public function resetPassword($array){
        return $this->userRepository->resetPassword($array);
    }

    public function firstStepResetPassword($array){
        return $this->userRepository->firstStepResetPassword($array);

    }

    public function getUserInfoDetails(Request $request){
        $language = $request->header('Accept-Language');
        return $this->userInfoRepository->getUserInfoDetails(
            $request['user_id'],
            ['id','name_'.$language],
            ['id','name_'.$language],
            ['*'],
        );
    }

    public function getUserDetails(Request $request){
        $language = $request->header('Accept-Language');
        return $this->userRepository->getUser($request['id'],['*']);
    }



    public function indexUser(Request $request){
        return $this->userInfoRepository->indexUserInfo($order= 'id', $sort = 'desc', $startDate = null, $endDate = null, $offset =0, $limit=15);
    }



}