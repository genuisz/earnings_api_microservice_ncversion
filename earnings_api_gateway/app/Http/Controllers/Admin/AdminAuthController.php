<?php 
namespace App\Http\Controllers\Admin;

use App\Traits\ProxyTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\BackendUser;
use Laravel\Passport\Bridge\UserRepository;


class AdminAuthController extends Controller{
    // public function username()
    // {
    //     return 'username';
    // }
    // public function adminUserLogin(Request $request)
    // {

    //     $admin_user = BackendUser::where('username', $request->username)
    //         ->firstOrFail();

    //     if (!Hash::check($request->password, $admin_user->password)) {
    //         return $this->failed('wrong password');
    //     }

    //     // $admin_user->last_login_at = Carbon::now();
    //     $admin_user->save();

    //     $tokens = $this->authenticate('admin');
    //     return $this->success(['token' => $tokens, 'user' => $admin_user]);
    // }


    // public function adminlogin(Request $request)
    // {
    //     // $request->validate([
    //     //     'email' => 'required|string|email',
    //     //     'password' => 'required|string',
    //     //     'remember_me' => 'boolean'
    //     // ]);

    //     $credentials = $request->only(['username', 'password']);
    //     if(!auth()->guard('web_admin')->attempt($credentials))
    //         return response()->json([
    //             'message' => 'Unauthorized'
    //         ], 401);

    //         //config(['auth.guards.api.provider' => 'admin']);    
    //     $user = $request->user('web_admin');
    //     $tokenResult = $user->createToken('Admin Access Token');
    //     $customScope = ["role"=>'admin'];


    //     if (!$internalToken = auth('jwt_admin')->claims($customScope)->attempt($credentials)) {
    //         abort(406);
    //     }
    //     //dd($internalToken);
    //     dd($tokenResult);
    //     $token = $tokenResult->token;
    //     dd($token);

        
    //     // Cache the internalToken  After that


    //     if ($request->remember_me)
    //         $token->expires_at = Carbon::now()->addWeeks(1);

    //     $token->save();

    //     return response()->json([
    //         'access_token' => $tokenResult->accessToken,
    //         'token_type' => 'Bearer',
    //         'expires_at' => Carbon::parse(
    //             $tokenResult->token->expires_at
    //         )->toDateTimeString()
    //     ]);
    // }

    public function testAdmin(Request $request){
        if($request->user()->can('test')){
            dd("admin right");
        }
        else{
            dd('cant');
        }

    }
}