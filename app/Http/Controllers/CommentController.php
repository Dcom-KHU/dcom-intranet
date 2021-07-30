<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use Storage;
use App\Board;
use App\Comment;
use App\Files;
use App\Dcomfile;
use Validator;
use Redirect;
use App\Logger;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class CommentController extends Controller
{
	// 로그인한 유저만 볼 수 있다.
	public function __construct(){
        $this->middleware('auth');
    }
	
	public function create(Request $request, $boardid){
		// 유효성 검사
		$validator = Validator::make($request->all(), [
			
		]);
	   
	   // 유효성 검사에서 걸리게 됬다면
		if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
		
		Comment::create([
            'boardid' => $boardid,
            'userid' => Auth::user()->userid,
            'content' => trim($request->content),
            'frontcomment' => 0, // 0은 앞 댓글이 없다는 뜻임 (메인 댓글)
			'anonymous' => $request->anonymous,
        ]);
		
		$type = Board::where('id',$boardid)->first()->boardid;
		if($request->anonymous==1){
			Logger::create([
				'command' => 'comment',
				'target' => $boardid,
				'type' => $type,
				'who' => '익명',
			]);
		}else{
			Logger::create([
				'command' => 'comment',
				'target' => $boardid,
				'type' => $type,
				'who' => Auth::user()->realname,
			]);
		}
		
		return Redirect::back();
	}
	
	// 댓글 수정
	public function modify(Request $request, $commentid){
		$comment = Comment::where('id', $commentid)->first();
		// 자신이 작성한 댓글이면
		if(Auth::user()->userid == $comment->userid){
			// 해당하는 댓글 id를 찾아 content를 업데이트 한다.
			Comment::where('id', $commentid)->update(['content' => trim($request->content), 'anonymous' => $request->anonymous]);
		}
		return Redirect::back();
	}
	
	// 댓글 삭제
	public function remove(Request $request, $commentid){
		$comment = Comment::where('id', $commentid)->first();
		// 자신이 작성한 댓글이면
		if(Auth::user()->userid == $comment->userid){
			$comment->delete();
			return 'true';
		}else
			return 'false';
	}
	
	// 댓글 답변
	public function reply(Request $request, $boardid, $commentid){
		// frontcomment는 앞 노드의 댓글을 가르킨다.
		// fontcomment가 0인 댓글은 가장 상위 노드의 댓글이다.
		
		// 유효성 검사
		$validator = Validator::make($request->all(), [

		]);
	   
	   // 유효성 검사에서 걸리게 됬다면
		if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
		
		Comment::create([
            'boardid' => $boardid,
            'userid' => Auth::user()->userid,
            'content' => trim($request->content),
            'frontcomment' => $commentid,
			'anonymous' => $request->anonymous,
        ]);
		
		$type = Board::where('id',$boardid)->first()->boardid;
		if($request->anonymous==1){
			Logger::create([
				'command' => 'reply',
				'target' => $boardid,
				'type' => $type,
				'who' => '익명',
			]);
		}else{
			Logger::create([
				'command' => 'reply',
				'target' => $boardid,
				'type' => $type,
				'who' => Auth::user()->realname,
			]);
		}
		return Redirect::back();
	}
	

	
	public function download($filename){
		$entry = Files::where('filename', '=', $filename)->firstOrFail();
		$headers = array('Content-Type:'.$entry->mime,);
		return response()->download(storage_path()."/app/".$entry->filename, $entry->original_filename, $headers);
	}
	
	public function dcomdownload($filename){
		$entry = Dcomfile::where('filename', '=', $filename)->firstOrFail();
		return response()->download(storage_path()."/app/".$entry->filename);
	}
 
	// 이미지를 업로드 하는 fucntion
	public function uploadimage(Request $request){
		$file = $request->file;
		$filename = $file->getFilename().'.'.$file->getClientOriginalExtension();
		$mime = $file->getClientMimeType();
		$original_filename = $file->getClientOriginalName();
		
		// 이미지는 storage/app 에 저장된다.
		Storage::disk('local')->put($file->getFilename().'.'.$file->getClientOriginalExtension(),  File::get($file));
		
		// 또한 DB에 저장됨
		Files::create([
            'userid' => Auth::user()->userid,
            'filename' => $filename,
            'mime' => $mime,
            'original_filename' => $original_filename,
            'type' => 'image',
            'download' => 0, // 처음 기본 다운로드 수는 0으로 지정한다.
        ]);
		
		// summernote에 이미지를 띄워주기 위해 다음을 반환한다.
		return $filename;
	}
	
	// 이미지를 제외한 파일을 업로드 하는 function
	public function uploadfile(Request $request){
		$file = $request->file;
		$filename = $file->getFilename().'.'.$file->getClientOriginalExtension();
		$mime = $file->getClientMimeType();
		$original_filename = $file->getClientOriginalName();
		
		// 이미지는 storage/app 에 저장된다.
		Storage::disk('local')->put($file->getFilename().'.'.$file->getClientOriginalExtension(),  File::get($file));
		
		// 또한 DB에 저장됨
		Files::create([
            'userid' => Auth::user()->userid,
            'filename' => $filename,
            'mime' => $mime,
            'original_filename' => $original_filename,
            'type' => 'file',
            'download' => 0, // 처음 기본 다운로드 수는 0으로 지정한다.
        ]);
		
		return $filename;
	}
}
