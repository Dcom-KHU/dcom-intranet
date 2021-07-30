<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('500', function()
{
    abort(500);
});

Route::get('/', function () {
	if(Auth::check()) return view('index');
	else return view('auth.login');
});

Auth::routes();

Route::get('/id/find', 'UserController@find_id_index');
Route::post('/id/find', 'UserController@find_id');


// 회원가입 이후 보여주는 페이지
Route::get('welcome', function (){return view('welcome');});
Route::get('error', function (){return view('errors.error');});

Route::group(['middleware' => ['auth']], function () {
	
	Route::get('crawler', 'CrawlerController@crawler');
	// 관리자의 회원 승락
	Route::get('auth', 'UserController@authindex');
	Route::post('auth/{userid}', 'UserController@auth');
	Route::post('auth/delete/{userid}', 'UserController@authdelete');
	Route::post('user/delete/{userid}', 'UserController@delete');
	Route::post('user/admin/{userid}', 'UserController@admin');
	Route::post('user/admin/delete/{userid}', 'UserController@admindelete');

	// 로그아웃이 POST 라우터로 되지 않음
	Route::get('logout', 'Auth\LoginController@logout');

	// 회원 목록
	Route::get('/users', 'UserController@users');

	// 내 정보 수정
	Route::get('/user/modify', function () {return view('user.modify');});
	Route::post('/user/modify','UserController@modify');

	/*
	// 그룹
	Route::get('/group/{type}', 'GroupController@index'); 

	Route::get('/group/{type}/create', function ($type) {return view('groups.create', ['type' => $type]);});
	Route::post('/group/{type}/create', 'GroupController@create');

	Route::get('/group/{type}/{boardid}', 'BoardController@groupindex');
	Route::post('/{boardid}/participation', 'GroupController@participation');
	*/

	// 그룹 게시판
	//
	Route::post('/{boardid}/comment/create', 'CommentController@create');
	Route::post('/comment/{commentid}/modify', 'CommentController@modify');
	Route::post('/comment/{commentid}/remove', 'CommentController@remove');
	
	
	// 답글기능  삭제
	// Route::post('/{boardid}/comment/{commentid}/reply', 'CommentController@reply');

	/*
	// 그롭 게시판 수정
	Route::get('/group/{type}/{boardid}/modify', function ($type, $boardid) { 
		if(Auth::user()->userid == App\Board::where('id',$boardid)->first()->userid) 
			return view('groups.modify', ['type' => $type, 'boardid' => $boardid]);
		else
			return redirect('/group/'.$type.'/'.$boardid);
	});
	Route::post('/group/{type}/{boardid}/modify', 'BoardController@groupmodify');
	*/

	Route::post('/uploadimage', 'CommentController@uploadimage'); // 이미지 파일 업로더
	Route::post('/uploadfile', 'CommentController@uploadfile'); // 파일 업로더
	Route::get('/download/{filename}','CommentController@download'); // 다운로드
	Route::get('/dcomdownload/{filename}','CommentController@dcomdownload'); // 다운로드

	// 글쓰기
	Route::get('/{boardid}/write', 'BoardController@writepage');
	Route::post('/{boardid}/write', 'BoardController@write');

	// 글수정
	Route::get('/{boardid}/{id}/modify', 'BoardController@modifypage');
	Route::post('/{boardid}/{id}/modify', 'BoardController@modify');

	// 글삭제
	Route::post('/{boardid}/{id}/remove', 'BoardController@remove');
	//Route::post('/group/{type}/{boardid}/remove', 'GroupController@remove');

	// 게시판들
	Route::get('/{boardid}','BoardController@index');
	Route::get('/{boardid}/{id}','BoardController@view');

	// 게시판에서 검색
	Route::get('/{boardid}/search/{mode}/{text}','BoardController@search');
	//Route::get('/group/{type}/search/{mode}/{text}','GroupController@search');

});
