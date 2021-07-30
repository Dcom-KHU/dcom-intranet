@extends('layouts.app')

@section('content')
<div class="col-md-8 col-md-offset-2">	
	<div class="form-group">
		<div class="col-md-12">
			@foreach ($boards as $board)
				<a href="/free/{{$board->id}}" style="text-decoration: none;">
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
						</div>
					</div>
				</a>
			@endforeach
		</div>
	</div>
</div>		
<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="form-group">
			<ul class="pagination" style="margin-left:30px; margin-top:10px;">
				@for ($i = 1; $i <= $boards->lastPage(); $i++)
					<li><a href="?page={{$i}}">{{$i}}</a></li>
				@endfor
			</ul>
		
			<div style="display:inline; float:right; margin-right:10px; margin-top:10px;">
				<a href="/free/write" class="btn btn-success" role="button">글쓰기</a>
			</div>
		</div>
		
		<div style="margin: 20px 0; width: 100%;">
			<div style="float:left; margin: 0 10px;">
				<select class="selectpicker" data-style="btn-success" data-width="fit" name="search">
					<option>제목</option>
					<option>내용</option>
					<option>제목+내용</option>
				</select>
			</div>
			<div style="float:right; margin: 0 10px;">
				<button type="submit" class="btn btn-success" onclick="search('free')">검색</button>
			</div>
			<div style="display: block; overflow: auto;">
				<input id="searchtext" type="text" class="form-control" name="searchtext" value="{{ old('searchtext') }}" required style="padding-top:4px;">
			</div>
		</div>	
	</div>
</div>

@endsection