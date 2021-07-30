<?php

namespace App\Http\Controllers;

use Redirect;
use Auth;
use Validator;
use App\User;
use App\Logger;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 회원 정보 수정
	public function modify(Request $request){
		
		$validations = [
			'email' => 'required|email|max:255',
			'realname' => 'required|max:255',
			'phone' => 'max:255',
			'admissionyear' => 'required|numeric|max:99|regex:/^[0-9]{2}$/',
		];

		$updates = [
            'email' => $request->email,
			'realname' => $request->realname,
			'phone' =>  preg_replace('/[^0-9]/','',$request->phone),
			'admissionyear' => $request->admissionyear,
		];
		
		if($request->password) {
			$validations['password'] = 'required|min:6|confirmed';
			$updates['password'] = bcrypt($request->password);
		}

		$validator = Validator::make($request->all(), $validations);
		
		if ($validator->fails()) {
			return redirect('/user/modify')->withErrors($validator)->withInput();
		}
		
		User::where('userid', Auth::user()->userid)->update($updates);
		
		return redirect('/user/modify');
	}

	public function users(){
		if(Auth::user()->admin == 1) {
			$users = User::where('confirm', 1)->latest('logintime')->get();
			return view('user.users',[
				'users' => $users,
			]);
		} else {
			return Redirect::back();
		}
			
	}
	
	// 현재 가입 승인중인 회원 보기
	public function authindex(){
		if(Auth::user()->admin == 1) {
			$users = User::where('confirm',0)->get();
			return view('auth.auth',[
				'users' => $users,
			]);
		} else {
			return Redirect::back();
		}
			
	}
	
	public function auth(Request $request, $userid){
		if(Auth::user()->admin == 1) {
			$users = User::where('userid',$userid)->update([
				'confirm' => 1,
			]);
			Logger::where('command', 'register')->where('target', $userid)->delete();
		}
		return Redirect::back();
	}
	
	public function authdelete(Request $request, $userid){
		if(Auth::user()->userid != $userid && Auth::user()->admin == 1) {
			$users = User::where('userid',$userid)->update([
				'confirm' => 0,
			]);
		}
		return Redirect::back();
	}
	
	public function admin(Request $request, $userid){
		if(Auth::user()->admin == 1) {
			$users = User::where('userid',$userid)->update([
				'admin' => 1,
			]);
		}
		return Redirect::back();
	}
	
	public function admindelete(Request $request, $userid){
		if(Auth::user()->userid != $userid && Auth::user()->admin == 1) {
			$users = User::where('userid',$userid)->update([
				'admin' => 0,
			]);
		}
		return Redirect::back();
	}
	
	public function delete(Request $request, $userid){
		if(Auth::user()->userid != $userid && Auth::user()->admin == 1) {
			$users = User::where('userid',$userid)->delete();
			Logger::where('command', 'register')->where('target', $userid)->delete();
		}
		return Redirect::back();
	}

    public function find_id_index() {
        return view('auth.id.email');
    }

    public function find_id(Request $request) {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email"
        ]);

        if ($validator->fails()) {
            return redirect('/id/find')->withErrors($validator)->withInput();
        }

        $res = User::where("email", $request->email)->where("realname", $request->name)->first();

        return view('auth.id.result', [
            "id" => $res === null ? null : $res->userid
        ]);
    }
}
