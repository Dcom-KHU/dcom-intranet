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
	const BOARDS = [
		"free" => [
			"name" => "자유게시판",
			"skin" => "default",
			'admin_only' => [],
		],
		"notice" => [
			"name" => "공지사항",
			"skin" => "default",
			'admin_only' => ['write'],
		],
		"jokbo" => [
			"name" => "족보",
			"skin" => "jokbo",
			'admin_only' => [],
		],
	];

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
		if(!array_key_exists($boardid, self::BOARDS))
			return Redirect::back();

		$boards = Board::where('boardid', $boardid)->latest('created_at')->paginate(10);
		
		$pagination = [];
		$pagination['start'] = $boards->currentPage() - ($boards->currentPage() - 1) % 5;
		$pagination['end'] = min($boards->lastPage(), $pagination['start'] + 4);
		$pagination['prev'] = $boards->currentPage() > 1 ? $boards->currentPage() - 1 : null;
		$pagination['next'] = $boards->currentPage() < $boards->lastPage() ? $boards->currentPage() + 1 : null;

		return view('boards.'.self::BOARDS[$boardid]['skin'].'.index',[
			'boardid' => $boardid,
			'name' => self::BOARDS[$boardid]['name'],
			'boards' => $boards,
			'pagination' => $pagination,
		]);
	}
	
	// 각 게시판에서 작성 페이지를 보여주는 함수이다.
	public function writepage($boardid){
		if(!array_key_exists($boardid, self::BOARDS))
			return Redirect::back();
		
		// 관리자만 쓸 수 있는 게시판
		if(in_array('write', self::BOARDS[$boardid]['admin_only']) && Auth::user()->admin == 0)
			return Redirect::back();

		return view('boards.'.self::BOARDS[$boardid]['skin'].'.write',[
			'boardid' => $boardid,
			'name' => self::BOARDS[$boardid]['name'],
		]);
	}
	
	// 각 게시판에 글을 작성하는 함수이다.
	public function write(Request $request, $boardid){
		if(!array_key_exists($boardid, self::BOARDS))
			return Redirect::back();
		
		// 관리자만 쓸 수 있는 게시판
		if(in_array('write', self::BOARDS[$boardid]['admin_only']) && Auth::user()->admin == 0)
			return Redirect::back();

		if($boardid === "jokbo") {
			$request->request->add(["title" => $request->subject . "|" . $request->professor]);
			$exist = Board::where("boardid", "jokbo")->where("title", $request->title)->first();
			if($exist !== null) {
				Comment::create([
					'boardid' => $exist->id,
					'userid' => Auth::user()->userid,
					'content' => trim($request->content),
					'frontcomment' => 0, // 0은 앞 댓글이 없다는 뜻임 (메인 댓글)
					'anonymous' => $request->anonymous ? 1 : 0,
				]);

				if($request->anonymous==1){
					Logger::create([
						'command' => 'comment',
						'target' => $exist->id,
						'type' => $boardid,
						'who' => '익명',
					]);
				}else{
					Logger::create([
						'command' => 'comment',
						'target' => $exist->id,
						'type' => $boardid,
						'who' => Auth::user()->realname,
					]);
				}

				return redirect($boardid . "/" . $exist->id);
			}
		}

		Board::create([
			'boardid' => $boardid,
			'userid' => Auth::user()->userid,
			'title' => $request->title,
			'content' => $request->content,
			'frontcomment' => 0,
			'anonymous' => $request->anonymous,
			'viewer' => 0, // 조회수는 0부터 시작한다.
		]);

		$id = DB::getPdo()->lastInsertId();
		
		if($request->anonymous==1){
			Logger::create([
				'command' => 'write',
				'target' => $id,
				'type' => $boardid,
				'who' => '익명',
			]);
		}else{
			Logger::create([
				'command' => 'write',
				'target' => $id,
				'type' => $boardid,
				'who' => Auth::user()->realname,
			]);
		}
		
		return redirect($boardid . "/" . $id);
	}
	
	public function remove(Request $request, $boardid, $id){
		if(!array_key_exists($boardid, self::BOARDS))
			return Redirect::back();

		$board = Board::where('id', $id)->first();
		// 자신이 작성한 글이면
		if(Auth::user()->admin == 1 || Auth::user()->userid == $board->userid){
			$board->delete();
			Logger::where('command', 'write')->where('target', $id)->delete();
			return 'true';
		}else
			return 'false';
	}
	
	// 각 게시판의 댓글을 보여준다.
	public function view(Request $request,$boardid ,$id){
		if(!array_key_exists($boardid, self::BOARDS))
			return Redirect::back();
		
		$board = Board::where('id', $id)->first();
		$comments = Comment::where('boardid', $id)->get();
			
		if($board){
			// 처음 다녀갔다면 viewer테이블에 추가하고
			if(!Viewer::where('boardid',$id)->where('userid',Auth::user()->userid)->first()){

				Viewer::create([
					'boardid' => $id,
					'userid' => Auth::user()->userid,
				]);
				// 조회수 1 증가
				$board->increment('viewer');
			}	
			return view('boards.'.self::BOARDS[$boardid]['skin'].'.view',[
				'board' => $board,
				'comments' => $comments,
			]);
		}
		return redirect($boardid);
	}
	
	// 게시판 검색
	public function search(Request $request, $boardid, $mode, $text){
		if(!array_key_exists($boardid, self::BOARDS))
			return Redirect::back();
		
		$text = strip_tags($text);
		
		if($mode == 'title'){
			$boards = Board::where('boardid', $boardid)->where('title', 'LIKE', '%'.$text.'%');
		}else if($mode == 'content'){
			$boards = Board::where('boardid', $boardid)->where('content', 'LIKE', '%'.$text.'%');
		}else if($mode == 'both'){
			$boards = Board::where('boardid', $boardid)->where('title', 'LIKE', '%'.$text.'%')->orwhere('boardid', $boardid)->where('content', 'LIKE', '%'.$text.'%');
		}else if($mode == 'subject'){
			$boards = Board::where('boardid', $boardid)->where('title', 'LIKE', '%'.$text.'%|%');
		}else if($mode == 'professor'){
			$boards = Board::where('boardid', $boardid)->where('title', 'LIKE', '%|%'.$text.'%');
		}else{
			return redirect($boardid);
		}

		$boards = $boards->latest('created_at')->paginate(10);
		
		$pagination = [];
		$pagination['start'] = $boards->currentPage() - ($boards->currentPage() - 1) % 5;
		$pagination['end'] = min($boards->lastPage(), $pagination['start'] + 4);
		$pagination['prev'] = $boards->currentPage() > 1 ? $boards->currentPage() - 1 : null;
		$pagination['next'] = $boards->currentPage() < $boards->lastPage() ? $boards->currentPage() + 1 : null;

		return view('boards.'.self::BOARDS[$boardid]['skin'].'.index',[
			'boardid' => $boardid,
			'name' => self::BOARDS[$boardid]['name'],
			'boards' => $boards,
			'pagination' => $pagination,
			'searchfield' => $mode,
			'searchtext' => $text,
		]);
	}
	
	public function modifypage(Request $request, $boardid, $id){
		if(!array_key_exists($boardid, self::BOARDS))
			return Redirect::back();

		$board = Board::where('id', $id)->first();
		// 자신이 작성한 글이면
		if(Auth::user()->admin == 1 || Auth::user()->userid == $board->userid)
			return view('boards.'.self::BOARDS[$boardid]['skin'].'.modify',[
				'boardid' => $boardid,
				'id' => $id,
				'board' => $board,
				'name' => self::BOARDS[$boardid]['name'],
			]);
		else
			return Redirect::back();
	}

	// 게시판 수정
	public function modify(Request $request, $boardid ,$id){
		if(!array_key_exists($boardid, self::BOARDS))
			return Redirect::back();
		
		if(Auth::user()->admin == 1 || Auth::user()->userid == Board::where('id',$id)->first()->userid){
			if($boardid === "jokbo") {
				$request->request->add(["title" => $request->subject . "|" . $request->professor]);
			}

			// 유효성 검사
			$validator = Validator::make($request->all(), [
				'title' => 'required|max:255',
				'content' => 'required',
			]);

			$anonymous = !empty($request->anonymous);
		   
			// 유효성 검사에서 걸리게 됬다면
			if ($validator->fails()) {
				return redirect($boardid.'/'.$id.'/modify')->withErrors($validator)->withInput();
			}
			
			Board::where('id',$id)->update([
				'title' => $request->title,
				'content' => $request->content,
				'anonymous' => $anonymous,
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

	public function jokbo_subject($keyword) {
		$res = DB::select("SELECT distinct substring_index(title, '|', 1) as f FROM boards WHERE boardid = 'jokbo' and title like concat('%', :keyword, '%|%') LIMIT 5", ["keyword" => $keyword]);
		$ret = [];
		foreach($res as $row) {
			$ret[] = $row->f;
		}
		return response()->json($ret);
	}

	public function jokbo_professor($keyword) {
		$res = DB::select("SELECT distinct substring_index(title, '|', -1) as f FROM boards WHERE boardid = 'jokbo' and title like concat('%|%', :keyword, '%') LIMIT 5", [$keyword]);
		$ret = [];
		foreach($res as $row) {
			$ret[] = $row->f;
		}
		return response()->json($ret);
	}

	public function jokbo_title($keyword) {
		$res = DB::select("SELECT distinct substring_index(title, '|', 1) as f FROM boards WHERE boardid = 'jokbo' and title like concat('%', :keyword2, '%|%') UNION SELECT distinct substring_index(title, '|', -1) as f FROM boards WHERE boardid = 'jokbo' and title like concat('%|%', :keyword1, '%') LIMIT 5", ["keyword1" => $keyword, "keyword2" => $keyword]);
		$ret = [];
		foreach($res as $row) {
			$ret[] = $row->f;
		}
		return response()->json($ret);
	}
	
}
