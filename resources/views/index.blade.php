@extends('layouts.layout')

@section('content')



		<div class="col-md-6 card-section">
			<div class="card-header">활동 요약</div>
		
			<div class="card-content">
				<ul class="content-list">
				
					<?php $lately_boards = App\Logger::latest('created_at')->take(5)->get()?>
					@foreach ($lately_boards as $lately_board)
						@if(App\Board::where('id', $lately_board->target)->first())
							<li>
								<div class="list-long">
									{{$lately_board->who}} 님이 
										<?php $board_title = App\Board::where('id', $lately_board->target)->first()->title?>
										
										@if($lately_board->type == 'project' || $lately_board->type == 'study')
											<a href="/group/{{$lately_board->type}}/{{$lately_board->target}}" class="inner-anchor">{{$board_title}}</a>
										@else
											<a href="/{{$lately_board->type}}/{{$lately_board->target}}" class="inner-anchor">{{$board_title}}</a>
										@endif
									
									
									@if($lately_board->command == 'comment')
										에 댓글을 작성하셨습니다.
									@elseif($lately_board->command == 'reply')
										에 답변을 작성하셨습니다.
									@elseif($lately_board->command == 'write')
										게시글을 작성하셨습니다.
									@endif
									
								
								</div>

								<div class="list-date hide-mobile">
									{{$lately_board->created_at->format('Y-m-d')}}
								</div>
							</li>
						
						@elseif($lately_board->command == 'register')
							<li>
								<div class="list-long">
								@if(Auth::user()->admin === 1)
									{{$lately_board->who}} 님이 <a href="/auth" class="inner-anchor">회원가입 승인</a>을 요청하셨습니다.
								@else
									{{$lately_board->who}} 님이 회원가입 승인을 요청하셨습니다.
								@endif
								</div>
								<div class="list-date hide-mobile">
									{{$lately_board->created_at->format('Y-m-d')}}
								</div>
							</li>
						@endif
					@endforeach
				</ul>
			</div>
		</div>

		<div class="card-section-divider">
			<div class="col-md-6 card-section">
				<div class="card-header">
					<a href="/notice">
						공지사항
					</a>
				</div>
			
				<div class="card-content">
					<ul class="content-list">
						<?php $boards = App\Board::where('boardid', 'notice')->latest('created_at')->take(5)->get()?>
						@foreach ($boards as &$board)
						<?php $commentcount = App\Comment::where('boardid', $board->id)->get()->count()?>
							<li>	
								<div class="list-long">
									<a href="/notice/{{$board->id}}" >
										<span>
											{{$board->title}}
											[{{$commentcount}}]
										</span>
									</a>
								</div>
								<div class="list-breakline">
									<div class="list-author">
										@if($board->anonymous == 1)
											<span class="numberbox">??</span>익명
										@elseif($board->anonymous == 0)
											<?php $user=App\User::where('userid', $board->userid)->first()?>
											<span class="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
										@endif
									</div>
									<div class="list-date">
										{{$board->created_at->format('Y-m-d')}}
									</div>
								</div>
							</li>
						@endforeach
					</ul>
				</div>
			</div>

			<div class="col-md-6 card-section">
				<div class="card-header">
					<a href="/free">
						자유게시판
					</a>
				</div>

				<div class="card-content">
					<ul class="content-list">
						<?php $boards = App\Board::where('boardid', 'free')->latest('created_at')->take(5)->get()?>
						@foreach ($boards as &$board)
						<?php $commentcount = App\Comment::where('boardid', $board->id)->get()->count()?>
							<li>	
								<div class="list-long">
									<a href="/free/{{$board->id}}" >
										<span>
											{{$board->title}}
											[{{$commentcount}}]
										</span>
									</a>
								</div>
								<div class="list-breakline">
									<div class="list-author">
										@if($board->anonymous == 1)
											<span class="numberbox">??</span>익명
										@elseif($board->anonymous == 0)
											<?php $user=App\User::where('userid', $board->userid)->first()?>
											<span class="numberbox">{{$user->admissionyear}}</span>{{$user->realname}}
										@endif
									</div>
									<div class="list-date">
										{{$board->created_at->format('Y-m-d')}}
									</div>
								</div>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>



@endsection