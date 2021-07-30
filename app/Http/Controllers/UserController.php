<?php

namespace App\Http\Controllers;

use Redirect;
use Auth;
use Validator;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 회원 정보 수정
	public function modify(Request $request){
		
		
		if(Auth::user()->email != $request->email){
			// 유효성 검사
			$validator = Validator::make($request->all(), [
				'email' => 'required|email|max:255|unique:users',
				'phone' => 'max:255',
			]);
			
			// 유효성 검사에서 걸리게 됬다면
			if ($validator->fails()) {
				return redirect('/user/modify')->withErrors($validator)->withInput();
			}
		}
		
		User::where('userid', Auth::user()->userid)->update([
            'email' => $request->email,
			'phone' =>  preg_replace('/[^0-9]/','',$request->phone),
		]);
		
		return redirect('/user');
	}
	
	// 현재 가입 승인중인 회원 보기
	public function authindex(){
		//if(Auth::user()->admin == 1){
			$users = User::where('confirm',0)->get();
			return view('auth.auth',[
				'users' => $users,
			]);
		//}else 
			//return Redirect::back();
			
	}
	
	public function auth(Request $request, $userid){
		//if(Auth::user()->admin == 1){
			$users = User::where('userid',$userid)->update([
				'confirm' => 1,
			]);
		//}
		return Redirect::back();
	}
}
