<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Student;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }*/

    public function login(){
        $user = User::where('email', request('email'))->first();

        if(!$user){
            return response()->json(array(
                'status' => 0 // user not found
            ));
        }
        else {
            if (!$user->email_verified) {
                return response()->json(array(
                    'status' => -1 // email not verified
                ));
            }
            elseif (Hash::check(request('password'), $user->password)){

                $userid = $user->userid;
                $student = Student::where('userid', $userid)->first();
                return response()->json(array(
                    'status' => 1, // Successful
                    'api_token' => $user->api_token,
                    'userid' => $user->userid
                ));
            }
            else {
                return response()->json(array(
                    'status' => 2 // Incorrect password
                ));
            }
        }
    }
}
