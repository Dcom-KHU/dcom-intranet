@extends('layouts.app')

@section('content')

<div class="col-md-12">


		<div class="col-md-6">
		
			<div class="panel panel-success" style="height:715px; overflow-x: hidden; overflow-y: auto;">
				<div class="panel-body">
				
					<?php $lately_boards = App\Logger::latest('created_at')->take(30)->get()?>
					@foreach ($lately_boards as $lately_board)
						@if(App\Board::where('id', $lately_board->target)->first())
						<div class="panel panel-success" id="alert">
							<div class="panel-body">
								<div class="col-md-9">
									
									
								
									{{$lately_board->who}} 님이 
										<?php $board_title = App\Board::where('id', $lately_board->target)->first()->title?>
										
										@if($lately_board->type == 'free')
											&nbsp;<a href="/free"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;자유 게시판</a> - 
										@elseif($lately_board->type == 'photo')
											&nbsp;<a href="/photo"><i class="fa fa-picture-o" aria-hidden="true"></i>&nbsp;사진 게시판</a> - 
										@elseif($lately_board->type == 'notice')
											&nbsp;<a href="/notice"><i class="fa fa-microphone" aria-hidden="true"></i>&nbsp;공지사항</a> - 
										@elseif($lately_board->type == 'study')
											&nbsp;<a href="/group/study"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;스터디</a> - 
										@elseif($lately_board->type == 'project')
											&nbsp;<a href="/group/project"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;프로젝트</a> - 
										@endif
										
										@if($lately_board->type == 'project' || $lately_board->type == 'study')
											<a href="/group/{{$lately_board->type}}/{{$lately_board->target}}">{{$board_title}}</a>
										@else
											<a href="/{{$lately_board->type}}/{{$lately_board->target}}">{{$board_title}}</a>
										@endif
									
									
									@if($lately_board->command == 'comment')
										에 댓글을 작성하셨습니다.
									@elseif($lately_board->command == 'reply')
										에 답변을 작성하셨습니다.
									@elseif($lately_board->command == 'participation')
										에 참여 하셨습니다.
									@elseif($lately_board->command == 'makegroup')
										에 이름으로 @if ($lately_board->type == 'project') 프로젝트@elseif($lately_board->type == 'study') 스터디@endif를 만들었습니다.
									@elseif($lately_board->command == 'write')
										라는 게시글을 작성하셨습니다.
									@endif
									
								
								</div>
								<div class="clear"></div>

								<div class="col-md-3">
									<i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;{{$lately_board->created_at->format('Y-m-d')}}
								</div>
								<div class="clear"></div>
							</div>
						</div> 
						
						@endif
					@endforeach
				</div>
			</div>
			<!--
			<div class="panel panel-warning">
				<div class="panel-body" style="color: #8a6d3b;">
					<div id='calendar'></div>
				</div>
			</div>
			-->
		</div>


		<div class="col-md-6">
			<?php $boards = App\Board::where('boardid', 'photo')->latest('created_at')->take(2)->get()?>
			@foreach ($boards as &$board)
			<?php $board->content = preg_replace("/https?\:\/\/dcomstudy\.com/", "https://dcom.club", $board->content); // 도메인 변경 대응 ?>
			<div class="col-sm-6" style="padding: 0 5px !important;">
				<a href="/photo/{{$board->id}}" style="text-decoration: none;">
					<div class="panel panel-success group-list">
						<div class="panel-heading">
							
							{{$board->title}}
							
							<?php $commentcount = App\Comment::where('boardid', $board->id)->get()->count()?>
							&nbsp;&nbsp;<i class="fa fa-commenting-o" aria-hidden="true"></i>[{{$commentcount}}]
								
							<span style="float:right;">
								@if($board->anonymous == 1)
									<span id="numberbox">??</span>익명
								@elseif($board->anonymous == 0)
									<?php $user=App\User::where('userid', $board->userid)->first()?>
									<span id="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
								@endif
								<i class="fa fa-pencil" aria-hidden="true"></i>
							</span>
						</div>
						<div class="panel-body" style="color:#333; line-height:10px;">
							<span style="float:left;">
								{{$board->created_at->format('Y-m-d')}}
							</span>
							<span style="float:right;">
								<i class="fa fa-eye" aria-hidden="true"></i>&nbsp;&nbsp;{{$board->viewer}}
							</span>
							
							<div id="thumnail"style="margin-top:20px;">
								<?php 
									
									$dom = new DOMDocument;
									$dom->loadHTML($board->content);
									$img = $dom->saveHTML($dom->getElementsByTagName('img')[0]);
										
									if(!$dom->getElementsByTagName('img')[0]) $img='이미지 없음ㅠ';
									
								?>
								
								{!! $img !!}
								
							</div>
						</div>
					</div>
				</a>
			</div>
			@endforeach
			
			<div class="clear"></div>

			<div style="margin-bottom:22px;">
			<?php $boards = App\Board::where('boardid', 'free')->latest('created_at')->take(4)->get()?>
				@foreach ($boards as &$board)
				<?php $board->content = preg_replace("/https?\:\/\/dcomstudy\.com/", "https://dcom.club", $board->content); // 도메인 변경 대응 ?>
				<a href="/free/{{$board->id}}" style="text-decoration: none;">
					<div class="panel panel-info free-list" style="margin-bottom: 15px !important;">
						<div class="panel-heading">
							
							{{$board->title}}
							
							<?php $commentcount = App\Comment::where('boardid', $board->id)->get()->count()?>
							&nbsp;&nbsp;<i class="fa fa-commenting-o" aria-hidden="true"></i>[{{$commentcount}}]
								
							<span style="float:right;">
								@if($board->anonymous == 1)
									<span id="numberbox">??</span>익명
								@elseif($board->anonymous == 0)
									<?php $user=App\User::where('userid', $board->userid)->first()?>
									<span id="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
								@endif
								<i class="fa fa-pencil" aria-hidden="true"></i>
							</span>
						</div>
						<div class="panel-body" style="color:#333; line-height:10px;">
							<span style="float:left;">
								{{$board->created_at->format('Y-m-d')}}
							</span>
							<span style="float:right;">
								<i class="fa fa-eye" aria-hidden="true"></i>&nbsp;&nbsp;{{$board->viewer}}
							</span>
						</div>
					</div>
				</a>
				@endforeach
			</div>
		</div>
		<div class="clear"></div>


	
</div>




@endsection