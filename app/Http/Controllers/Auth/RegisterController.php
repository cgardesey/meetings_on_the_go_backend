<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Instructor;
use App\Mail\PasswordResetLinkSent;
use App\Mail\VerificationEmailSent;
use App\PasswordReset;
use App\Student;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('guest');
//    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
//            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function store()
    {
        $user = User::where('email', request('email'))->first();
        if (!$user) {
            $userid = Str::random(80);
            $created_user = User::forceCreate([
                'userid' => $userid,
                'email' => request('email'),
                'password' => Hash::make(request('password')),
                'role' => request('role'),
                'api_token' => Str::random(80),
                'confirmation_token' => Str::random(40)
            ]);
            if ($created_user->role == 'student') {
                Student::forceCreate([
                    'userid' => $userid
                ]);
            } else if ($created_user->role == 'instructor') {
                Instructor::forceCreate([
                    'userid' => $userid
                ]);
            }
            Mail::to($created_user->email)->send(
                new VerificationEmailSent($created_user)
            );

            return '1';
        } else {
            return '0';
        }
    }

    protected function confirmEmail($email, $token)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            flash('Email not found.')->error();
            return Redirect::to("register/confirm/$token");
        }

        $user->confirmation_token = null;
        $user->email_verified = 1;

        $user->save();

        flash('Email successfully verified. Your can now proceed to login!')->info();
        return Redirect::to("register/confirm/$token");
    }


}
