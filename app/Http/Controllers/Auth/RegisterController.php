<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

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
	
	// 이메일이 아닌 회원 아이디로 로그인
	protected $username = 'userid';

	// https://laracasts.com/discuss/channels/laravel/laravel-53-disable-autologin-after-registration 참고
	public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
	
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/welcome';

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
            'userid' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
			'password' => 'required|min:6|confirmed',
			'realname' => 'required|max:255',
			'phone' => 'max:255',
			'admissionyear' => 'required|numeric|max:99',
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'userid' => $data['userid'],
            'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'realname' => $data['realname'],
			'phone' =>  preg_replace('/[^0-9]/','',$data['phone']),
			'admissionyear' => $data['admissionyear'],
        ]);
    }
}
