<?php

namespace App\Http\Controllers\Auth;

use App\Models\CustomUser as User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'ch_use_login' => ['required', 'string', 'max:255'],
            'ch_use_mail' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'ch_use_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\CustomUser
     */
    protected function create(array $data)
    {
        return User::create([
            'ch_use_login' => $data['ch_use_login'],
            'ch_use_mail' => $data['ch_use_mail'],
            'ch_use_password' => Hash::make($data['ch_use_password']),
        ]);
    }
}
