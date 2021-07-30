<?php

namespace App\Http\Controllers;

use Validator;
use App\Group;
use App\GroupsUsers;
use App\Board;
use App\User;
use App\Logger;
use Auth;
use Redirect;
use DB;
use Illuminate\Http\Request;

class GroupController extends Controller
{
	// 로그인한 유저만 볼 수 있다.
	public function __construct(){
        $this->middleware('auth');
    }
	
	public function index($type){
		$groups = Board::where('boardid', $type)->latest('created_at')->paginate(4);
		return view('groups.'.$type,[
			'groups' => $groups,
		]);
	}
	
	// 그룹 게시판 검색
	public function search(Request $request, $type, $mode, $text){
		
		$groups = Group::where('type', $type)->latest('created_at')->paginate(4);
		$text = strip_tags($text);
		
		if($mode == 'title'){
			$groups = Group::where('title', 'LIKE', '%'.$text.'%')->latest('created_at')->paginate(4);
		}else if($mode == 'content'){
			$groups = Group::where('description', 'LIKE', '%'.$text.'%')->latest('created_at')->paginate(4);
		}else if($mode == 'both'){
			$groups = Group::where('title', 'LIKE', '%'.$text.'%')->orwhere('description', 'LIKE', '%'.$text.'%')->latest('created_at')->paginate(4);
		}else{
			return redirect('/group/'.$type);
		}
		
		return view('groups.'.$type,[
			'groups' => $groups,
		]);
	}
	
	public function participation(Request $request, $boardid){
		// 그룹에 참여해 있지 않다면
		// groupid와 boradid는 같다.
		if(!GroupsUsers::where('userid', Auth::user()->userid)->where('groupid', $boardid)->first()){
			// 그룹과 회원의 관계성을 연결해주는 데이터베이스 생성
			GroupsUsers::create([
				'groupid' => $boardid,
				'userid' => Auth::user()->userid,
			]);
			
			$type = Group::where('boardid', $boardid)->first()->type;
			Logger::create([
				'command' => 'participation',
				'target' => $boardid,
				'type' => $type,
				'who' => Auth::user()->realname,
			]);
			
			Group::where('id', $boardid)->increment('participation');
		}
		return Redirect::back();
	}
	
	// 그룹 생성
	// type는 project또는 study
	public function create(Request $request, $type){
		// 유효성 검사
		$validator = Validator::make($request->all(), [
			'type' => ["required","regex:(project|study)"], // project, study
			'title' => 'required|max:255',
			'description' => 'required|max:255',
			'git' => 'max:255',
		]);
	   
	   // 유효성 검사에서 걸리게 됬다면
		if ($validator->fails()) {
            return redirect('group/'.$type.'/create')->withErrors($validator)->withInput();
        }
		
		// 현재 접속한 세션의 유저 아이디를 찾는다.
		$user =  User::where('userid', Auth::user()->userid)->first();
		$git="https://github.com/";
		if($request->git) $git = $request->git;
		
		// 그룹이 만들어짐 동시에 그룹에 관련된 게시판이 생성된다.
		Board::create([
			'boardid' => $type,
            'userid' => $user->userid,
            'title' => $request->title,
			'content' => $request->description,
        ]);
				
		$id = DB::table('boards')->select('id')->orderBy('id', 'DESC')->first()->id;
		
		Logger::create([
			'command' => 'makegroup',
			'target' => $id,
			'type' => $type,
			'who' => Auth::user()->realname,
		]);
		
		// 그룹 데이터베이스 생성
		Group::create([
			'boardid' => $id,
            'userid' => $user->userid,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'participation' => 1, // 처음 참가자는 1명부터 시작한다.
			'git' => $git,
        ]);
				
		// 그룹과 회원의 관계성을 연결해주는 데이터베이스 생성
		GroupsUsers::create([
            'groupid' => $id,
            'userid' => $user->userid,
        ]);
		
		return redirect('group/'.$type);
	}
	
	public function remove(Request $request, $type, $boardid){
		$board = Board::where('id', $boardid)->first();
		$group = Group::where('boardid', $boardid)->first();
		$loggers = Logger::where('target',  $boardid)->get();
		if(Auth::user()->userid == $board->userid){
			$board->delete();
			$group->delete();
			
			foreach ($loggers as $logger) {
				$logger->delete();
			}
						
			return 'true';
		}else
			 return 'false';
	}
}
