<?php
namespace App\Repository;

use App\Event\ChangedPasswordEvent;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\ForgotPasswordTooManyException;
use App\Models\UserInfo;
use App\Models\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Closure;
use Exception;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Arr;
use App\Repository\ResetPasswordRepository;

class UserRepository extends AbstractRepository implements UserRepositoryInterface{
    public function model(){
        return  'App\\Models\\User';
     }

     public function createUser(array $array){

         $array['remember_token']='test';
         $array['password'] =  Hash::make($array['password']);
        return $this->saveData($array,false);
     }

     public function createUserInfo(array $array){
         $userInfo = new UserInfo($array);
         $userInfo->reward_point = '0';
         $userInfo->registered_ip= $array['registered_ip'];
         $userInfo->recent_ip= $array['recent_ip'];
         $userInfo->status='1';
         $userInfo->notification_status = '1';
         $this->model->userinfo()->save($userInfo);
         $this->model->refresh();
         return $this->model->userinfo();
     }
 
     public function updateUser(array $array){
        $userId = auth('jwt')->user()->id;
        $this->setModel($this->getOneById($userId));
        $this->updateData($array);
     }

 
     public function getUser($id,$userColumn){
        $user =  $this->getOneById($id,$userColumn);
        $this->setModel($user);
        return $user;
        }

     public function getUserByEmail($email){
         try{
            $user = $this->model->where('email',$email);
         }
         catch(Exception $e){
             throw new DataNotFoundException();
         }
         return $user;
     }    
     public function getUserInfoDetails($id,$query){
         return $this->model::withHas('userinfo',function ($q) use ($id,$query){
             $q->where('id',$id)->select($query);
         });
     }    
     
     public function getUserInfo(){
         return $this->model->userinfo()->get();
     }
 
     public function deleteUser(array $array){
         $this->setModel($this->getOneById($array['user_id']));
         $this->deleteData();

     }

     public function generateVerificationURL($user){
        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            if ($user instanceof User) {
                $url = $user->generateVerificationURL();
                $data['url'] = $url;
                $data['email'] = $user->getEmailForVerification();
            }
           
        }
        return $data;
     }

     public function indexUser($order = 'desc', $sort = 'id', $startDate = null, $endDate = null, $offset = 0, $limit = 15)
     {
        return $this->all($order,$sort,$offset,$limit);
     }

     public function verifyUser($id)
     {
         return $this->model->foreceFill(['email_verified_at'=>Carbon::now()])->save();
     }

     public function forgotPassword($array){
         $status = Password::sendResetLink($array,function($user,$token){
            $this->setModel($user);
            //$url = $this->model->generateResetPasswordURL($token);
            $url = $this->model->generateResetPasswordURL2($token);
            $data['url'] = $url;
            $data['email']= $user->getEmailForPasswordReset();
            event(new ChangedPasswordEvent($data));
            
         });
         
         if($status==PasswordBroker::RESET_THROTTLED) {
            throw new ForgotPasswordTooManyException($status);
         }
         else{
             
         }
         
     }

     public function resetPassword($array){

        
    //     $status = Password::reset(($array),
    //     function($user,$password) {
    //         $user->forceFill([
    //             'password'=>Hash::make($password)
    //         ]);
    //         $user->save();

    //         event(new ChangedPasswordEvent($user));

    //     }
    // );

    

            $status = $this->reset($array,function($user,$password) {
                $user->forceFill([
                    'password'=>Hash::make($password)
                ]);
                $user->save();
    
                event(new ChangedPasswordEvent(Arr::only($user->toArray(),['email','username'])+['pass_reset_time'=>Carbon::now()->toDateTimeString()]));
    
            });
            
            if($status==Password::INVALID_TOKEN || $status==Password::INVALID_USER){
                throw new DataNotFoundException();
            }


   
     }

     public function reset(array $crdentials , Closure $callback){
        $user = $this->validateReset(Arr::only($crdentials,['id','token']));
        if(!$user instanceof CanResetPassword){
            return $user;
        }

        $password = $crdentials['password'];

        $callback($user, $password);

        Password::deleteToken($user);

        return Password::PASSWORD_RESET;
        
     }

     public function validateReset(array $crdentials){
         if(is_null($user = Password::getUser($crdentials))){
             return Password::INVALID_USER;
         }

         if(! Password::tokenExists($user,$crdentials['token'])){
             return Password::INVALID_TOKEN;
         }




         return $user;


     }



     public function firstStepResetPassword($array){
         $user = $this->getOneById($array['id']);
         $userName = $user->username;
         // TO-DO  The data token,id,expires and signature  should save in front end and proceed the reset password
         // return view() ....
         


     }

     

}