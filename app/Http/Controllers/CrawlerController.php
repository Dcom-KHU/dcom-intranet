<?php

namespace App\Http\Controllers;

use Auth;
use Redirect;
use App\Dcomfile;
use App\Board;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use DB;
use Validator;

class CrawlerController extends Controller
{

	function curl($url){
				
		//echo 'url : '.$url."<br />";
	
		$cookies = 'MAIN=FILE%5FReferer=True; ASPSESSIONIDSSSRSCRD=BBENKLNCLAHKAEOOBFGEOBAD; ASPSESSIONIDSARSSDQA=LLJPKLNCDPOIECCFAAGPGBKE; LOGIN=saveid=off&iplevel=2; cookieinfouser=f42dae2f62d07443a8646f4fed14c4a5d03ab70eeae7db32cb008328a703a0c6f5788ff3f508ce1b0c61136991d86308179f51e007c5cf74a1b97d5caf0f8da53b522c608bfc6638303a5eab039077cbdbfb12a7a4a14162ba6995b38e10ec9d446f8e9f5b01c31c948886698753c28cce195e55145b9368e64ac1fe724d176ad199e5b3403285b1f7dce3a64a86cc54; NSVC=||; CFN=a758a44671d3aa395d4d23732b2dd77654e89e4503f45d303ca0a8e9fd0ded89eb8ae55322f96480a8b4cbc07f7df5c3a98af5b012f3799482ee1b11be6a561fa66d5cab2c1c2c179ede8ead578989fb90633400948c8ab9115ca42dd5df926f9e15a6ce3249f9854e6d0f37f495d18d38f8b2b0d2bb76658db770a527678d223415bb182e7ba4eb6034c96b5cead593b9d6c1868eb4f959e2da5d164a5f535c8115168233fe3ce8944936390b59ef070fe3a754099734846426f1bb2f4f664283ccd99a76d118880d4d7e6af99f21b493a71f6b49445a6c9fb5f0283acb42f965721e163177915e4b862b12d926996c704a09edaa295b09ce0369e311a19905; C3RD=435e33cbbcf515462f7483757059b33f11f84fa403a567607624ecc75e2c5201d76bc020c708b4eb57a40a1e8d8f2b91f8515f841d7d8517bbcd5e32f448cc563183027355c53628a87179bca9ff1309e47dc4462fe56d77c522249e4da4177efa76c121eb7b15c4b19475c2f241893f39133fd248b9fb47bb9eb7b82b58ef0f30ee3fddf9be8ae2ad04dcebc47f6275; ETC=enc%5Fnateon%5Femail=0AFDCB4369A7E9F834A3EF028E99EFAE132E07D1E65F3869&cpinfo=fcdcf5be4db3c2bc8718b85440ef49ccdd16d450da45289cc8b051c4b438cd0857fd7dccf1c5e2c44a6c5d84bba2ba97237e8467f06ba612cbcece132bf7b0a0&NCFLAG=n&CFN=a758a44671d3aa395d4d23732b2dd77654e89e4503f45d303ca0a8e9fd0ded89eb8ae55322f96480a8b4cbc07f7df5c3a98af5b012f3799482ee1b11be6a561fa66d5cab2c1c2c179ede8ead578989fb90633400948c8ab9115ca42dd5df926f9e15a6ce3249f9854e6d0f37f495d18d38f8b2b0d2bb76658db770a527678d223415bb182e7ba4eb6034c96b5cead593b9d6c1868eb4f959e2da5d164a5f535c8115168233fe3ce8944936390b59ef070fe3a754099734846426f1bb2f4f664283ccd99a76d118880d4d7e6af99f21b493a71f6b49445a6c9fb5f0283acb42f965721e163177915e4b862b12d926996c704a09edaa295b09ce0369e311a19905; UD3=71b1cb1f8e761ccca9fd69abda07f47; ndrn=|QUxHNUs0RzA=|; UA3=|QUxHNUs0RzA=|; RDB=01|20|||KR|; UAKD=3; cookieclubinfo2=24d384cef7ea0d73f35296c3e0f36fbd1f2c7de4f1544a2cf13ee5190dfae6171aedcc3a82c01bc16e48d3858b02bfb1065712535b5a33f74852c7f9bbc0321e6537e49e7dd4ad7eeff90e85182d0e18b88a38ad106310d06020042932972eaac0a1da3c8a6ee229f9f433d0a79dfa82a9f09b58794fb930b3836eb1fce09803434221ed2abcec2817a9f6877c17463d1618a5b86aab97b091e73223149bbdcc26e32850a1fd59ef432630a5b7ee08c1d1be447e0697d42389f2b42c4d09d06e3e1fe48d1b828070cb5ab8650012b620cc2bd662c34b3c940c7b086a1686a951; cookieclubinfo=TBVCN7MzZLPEKSQjnT%2fhGLvOxIVenEeR04oDBQdF9cMUlKeM7z0KLnWOuom%2ftX6GhUCsbUFCYPaWLwOi2o9%2f9BQ%2buAQWb7Sc2BhA3eKQB820udC%2fQQUd84bhDLIw9j3KqFZZhyrY6QGmYtVclORyLA38YutoDK4%3d%00; cookieclubinfo3=TBVCN7MzZLM%3D; CLUB=inside%5Fsrc=';
		
		
		$ch = curl_init();  
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko)');
		curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		$output=curl_exec($ch);
	 
		curl_close($ch);
		
		return htmlspecialchars(iconv( "euc-kr","utf-8", $output));
	}
	
	function upload($url, $realname, $original_filename){
		
		if(strlen($original_filename) > 0){
			$url = htmlspecialchars_decode($url);
					
			$extension = pathinfo($original_filename, PATHINFO_EXTENSION);

			$filename = md5(uniqid(rand(), true)).'.'.$extension;
			//$save = file_put_contents(storage_path()."/app/".$filename, $file);
			$save = file_put_contents(storage_path()."/app/".$filename, fopen($url, 'r'));
			if($save || (!Dcomfile::where('realname', $realname)->where('original_filename', $original_filename)->first()) ){
						  
				Dcomfile::create([
					'filename' => $filename,
					'realname'=> $realname,
					'original_filename'=> $original_filename,
				]);
				return "http://dcomstudy.com/dcomdownload/".$filename;
			}
		}
		return false;
	}
	
	function indexof($data, $find){
		return strpos($data, htmlspecialchars($find));
	}
	
    // 싸이월드 크롤링하는 함수
	public function crawler(){
		
		return;
		// 어드민 일 때만
		if(Auth::user()->admin == 1){
			$data = $this->curl("http://club.cyworld.com/ClubV1/Home.cy/51492012");
			
			$data = substr($data, $this->indexof($data, "<strong>과제/족보/솔루션</strong></li>"));
			$data = substr($data,0,$this->indexof($data,"<li class='sLine'>구분선----</li>"));
			
			while($this->indexof($data, "/club/board/general/list.asp?") > -1){
								
				$data = substr($data, $this->indexof($data, "/club/board/general/list.asp?"));
				$url = "http://club.cyworld.com".htmlspecialchars_decode(substr($data,0, $this->indexof($data, "board_type=1") + strlen("board_type=1")))."&show_type=2";
				$url = htmlspecialchars_decode($url);
				
				

			
				$subject = substr($data, $this->indexof($data, "title='") + strlen(htmlentities("title='")));
				$subject = substr($subject,0, $this->indexof($subject, "("));
				
				echo $subject.' : '.$url.'</br>';
				
				
				// 데이터베이스 저장
				if(!Board::where('title', $subject)->first()){
					Board::create([
						'boardid' => 'jokbo',
						'userid' => 'dcom',
						'title' => $subject,
						'content' => '',
						'frontcomment' => 0,
						'anonymous' => 0,
						'viewer' => 0, // 조회수는 0부터 시작한다.
					]);
				}
				
				// 페이지 while 문
				$page = 1;
				while(true){
					$list = $this->curl($url.'&cpage='.$page);
					
					if($this->indexof($list, '게시물이 없습니다') > -1) break;

					while($this->indexof($list, 'target="_self">') > -1){
						
						$chunk = substr($list, $this->indexof($list, 'target="_self">') + strlen(htmlentities('target="_self">')));
						$chunk =  substr($chunk, 0, $this->indexof($chunk, "<!-- //본문 -->"));
						
						$title = substr($chunk, 0, $this->indexof($chunk, "</a>"));
						echo $title."</br>";
						
						$name = substr($chunk, $this->indexof($chunk, 'class="nameui">') + strlen(htmlentities('class="nameui">')));
						$name =  trim(substr($name, 0, $this->indexof($name, "</a>")));
						
						//echo $name."</br>";
						
						$html = '';
						
						while($this->indexof($chunk, "<a href='") > -1){
							
							$link = substr($chunk, $this->indexof($chunk, "<a href='") + strlen(htmlentities("<a href='")));
							$link = substr($link, 0, $this->indexof($link,"' target=_blank"));
							$link = "http://club.cyworld.com".urldecode($link);
							$link = iconv("euc-kr","utf-8", $link);
							
							
							$filename = substr($link, $this->indexof($link, "file_nm=") + strlen(htmlentities("file_nm=")));
							$filename = substr($filename, 0, $this->indexof($filename,"&"));
							$filename =  urldecode($filename);
							
							
							// 파일을 Dcomfile 테이블에 저장
							$fileupload = $this->upload($link, $name, $filename);
														
							$html = $html.'<a href="'.$fileupload.'">'.$filename.'</a></br>';
							
							$chunk = substr($chunk, $this->indexof($chunk, "<a href='") + strlen(htmlentities("<a href='")));
						}
						
						
						$content = htmlspecialchars_decode(substr($chunk, $this->indexof($chunk, '<!-- 본문 -->') + strlen(htmlentities('<!-- 본문 -->'))));
						$html = $html.$content;
						
						//echo $html."</br>";
						
						$lastid = DB::table('boards')->select('id')->orderBy('id', 'DESC')->first()->id;
						
						// Comment 쓴다.
						if(!Comment::where('content', $html)->where('boardid', $lastid)->first()){
							Comment::create([
								'boardid' => $lastid,
								'userid' =>  $name,
								'content' => $html,
								'frontcomment' => 0,
								'anonymous' => 0,
							]);
						}
						$list = substr($list, $this->indexof($list, 'target="_self">') + strlen(htmlentities('target="_self">')));
					}
					$page++;
				}
				
				$data = substr($data, $this->indexof($data, "/club/board/general/list.asp?") + strlen(htmlentities("/club/board/general/list.asp?")));
			}
		}
		else{
			return Redirect::back();
		}
	}

}
