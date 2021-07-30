<?php

namespace App\Http\Controllers;
use Redirect;
use Auth;
use App\Comment;
use App\Board;
use App\Group;
use App\Viewer;
use App\Logger;
use DB;
use Validator;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    //
	// 로그인한 유저만 볼 수 있다.
	public function __construct(){
        $this->middleware('auth');
    }
	
	// 게시판 중에서 그룹 게시판의 댓글을 보여준다.
	public function groupindex($type, $boardid){
		$board = Board::where('id', $boardid)->first();
		$group = Group::where('boardid', $boardid)->first();
		
		if($board){
		
			$comments = Comment::where('boardid', $boardid)->get();
			if($comments) {
				// 도메인 변경 대응
				foreach($comments as &$comment) {
					$comment->content = preg_replace("/https?\:\/\/dcomstudy\.com/", "https://".$_SERVER['SERVER_NAME'], $comment->content);
				}
			}
				return view('groups.group',[
					'board' => $board,
					'group' => $group,
					'comments' => $comments,
				]);
		
		}

		return redirect('/group/'.$type);
	}
	
	// 각 게시판에서 글을 가져오는 함수이다.
	public function index($boardid){
		$boards = Board::where('boardid', $boardid)->latest('created_at')->paginate(4);
		//if($boardid == 'jokbo')
		//	$boards = Board::where('boardid', $boardid)->latest('created_at')->get();

		// 도메인 변경 대응
		foreach($boards as &$board) {
			$board->content = preg_replace("/https?\:\/\/dcomstudy\.com/", "https://".$_SERVER['SERVER_NAME'], $board->content);
		}
		
		return view('boards.'.$boardid,[
			'boards' => $boards,
		]);
	}
	
	// 각 게시판에 글을 작성하는 함수이다.
	public function write(Request $request, $boardid){
		
		// 관리자만 쓸 수 있는 게시판
		if(Auth::user()->admin == 1 && $boardid == 'notice'){
			Board::create([
				'boardid' => 'notice',
				'userid' => Auth::user()->userid,
				'title' => $request->title,
				'content' => $request->content,
				'frontcomment' => 0,
				'anonymous' => $request->anonymous,
				'viewer' => 0, // 조회수는 0부터 시작한다.
			]);
			
			Logger::create([
				'command' => 'write',
				'target' => DB::getPdo()->lastInsertId(),
				'type' => 'notice',
				'who' => Auth::user()->realname,
			]);
			
			return redirect('notice');
		}
		
		if($boardid != 'notice'){
			Board::create([
				'boardid' => $boardid,
				'userid' => Auth::user()->userid,
				'title' => $request->title,
				'content' => $request->content,
				'frontcomment' => 0,
				'anonymous' => $request->anonymous,
				'viewer' => 0, // 조회수는 0부터 시작한다.
			]);
			
			if($request->anonymous==1){
				Logger::create([
					'command' => 'write',
					'target' => DB::getPdo()->lastInsertId(),
					'type' => $boardid,
					'who' => '익명',
				]);
			}else{
				Logger::create([
					'command' => 'write',
					'target' => DB::getPdo()->lastInsertId(),
					'type' => $boardid,
					'who' => Auth::user()->realname,
				]);
			}
		}
		
		return redirect($boardid);
	}
	
	public function remove(Request $request, $boardid, $id){
		$board = Board::where('id', $id)->first();
		// 자신이 작성한 글이면
		if(Auth::user()->userid == $board->userid){
			$board->delete();
			return 'true';
		}else
			return 'false';
	}
	
	// 각 게시판의 댓글을 보여준다.
	public function comment(Request $request,$boardid ,$id){
		
		$board = Board::where('id', $id)->first();
		$comments = Comment::where('boardid', $id)->get();

		if($comments) {
			// 도메인 변경 대응
			foreach($comments as &$comment) {
				$comment->content = preg_replace("/https?\:\/\/dcomstudy\.com/", "https://".$_SERVER['SERVER_NAME'], $comment->content);
			}
		}
			
		if($board){
			// 도메인 변경 대응
			$board->content = preg_replace("/https?\:\/\/dcomstudy\.com/", "https://".$_SERVER['SERVER_NAME'], $board->content);

			// 처음 다녀갔다면 viewer테이블에 추가하고
			if(!Viewer::where('boardid',$id)->where('userid',Auth::user()->userid)->first()){

				Viewer::create([
					'boardid' => $id,
					'userid' => Auth::user()->userid,
				]);
				// 조회수 1 증가
				$board->increment('viewer');
			}	
			return view('boards.comment',[
				'board' => $board,
				'comments' => $comments,
			]);
		}
		return redirect($boardid);
	}
	
	// 게시판 검색
	public function search(Request $request, $boardid, $mode, $text){
		
		//$boards = Board::where('boardid', $boardid)->latest('created_at')->paginate(4);
		$text = strip_tags($text);
		
		if($mode == 'title'){
			$boards = Board::where('boardid', $boardid)->where('title', 'LIKE', '%'.$text.'%')->latest('created_at')->paginate(4);
		}else if($mode == 'content'){
			$boards = Board::where('boardid', $boardid)->where('content', 'LIKE', '%'.$text.'%')->latest('created_at')->paginate(4);
		}else if($mode == 'both'){
			$boards = Board::where('boardid', $boardid)->where('title', 'LIKE', '%'.$text.'%')->orwhere('content', 'LIKE', '%'.$text.'%')->latest('created_at')->paginate(4);
		}else{
			return redirect($boardid);
		}

		// 도메인 변경 대응
		foreach($boards as &$board) {
			$board->content = preg_replace("/https?\:\/\/dcomstudy\.com/", "https://".$_SERVER['SERVER_NAME'], $board->content);
		}
		
		return view('boards.'.$boardid,[
			'boards' => $boards,
		]);
		
	}

	// 게시판 수정
	public function modify(Request $request, $boardid ,$id){
		
		if(Auth::user()->userid == Board::where('id',$id)->first()->userid){
			// 유효성 검사
			$validator = Validator::make($request->all(), [
				'title' => 'required|max:255',
				'content' => 'required',
				'anonymous' => ["required","regex:(0|1)"], // 0, 1
			]);
		   
			// 유효성 검사에서 걸리게 됬다면
			if ($validator->fails()) {
				return redirect($boardid.'/'.$id.'/modify')->withErrors($validator)->withInput();
			}
			
			Board::where('id',$id)->update([
				'title' => $request->title,
				'content' => $request->content,
				'anonymous' => $request->anonymous,
			]);
		
		}
		
		return redirect($boardid.'/'.$id);
	}
	
	// 게시판 중에서 그룹 수정
	public function groupmodify(Request $request, $type, $boardid){
		
		if(Auth::user()->userid == Board::where('id',$boardid)->first()->userid){
			// 유효성 검사
			$validator = Validator::make($request->all(), [
				'type' => ["required","regex:(project|study)"], // project, study
				'title' => 'required|max:255',
				'description' => 'required|max:255',
				'git' => 'max:255',
			]);
		   
		   // 유효성 검사에서 걸리게 됬다면
			if ($validator->fails()) {
				return redirect('group/'.$type.'/'.$boardid.'/create')->withErrors($validator)->withInput();
			}
			
			Group::where('boardid', $boardid)->update([
				'title' => $request->title,
				'description' => $request->description,
				'git' => $request->git,
			]);
			
			Board::where('id', $boardid)->update([
				'title' => $request->title,
				'content' => $request->description,
			]);
		}
		return redirect('group/'.$type);
	}
	
}
