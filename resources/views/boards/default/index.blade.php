@extends('layouts.layout')

@section('content')
<script>
$(function() {
	$(".search-form").on("submit", () => {
		search("{{$boardid}}");
		return false;
	});
})
</script>
<div class="card-section">	
	<div class="card-header">
		{{$name}}
	</div>
	<div class="card-content">
		<ul class="content-list board-list">
			@foreach ($boards as $board)
				<li>
					<div class="list-long">
						<a href="/{{$boardid}}/{{$board->id}}">
							{{$board->title}}
							<?php $commentcount = App\Comment::where('boardid', $board->id)->get()->count()?>
							[{{$commentcount}}]
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
			@if(count($boards) === 0)
				<li>
					<div class="list-long">
						게시물이 없습니다.
					</div>
				</li>
			@endif
		</ul>
		<ul class="pagination">
			@if($pagination['prev'])
				<li>
					<a href="?page={{$pagination['prev']}}">
						<i class="fa fa-angle-left"></i>
					</a>
				</li>
			@endif
			@for ($i = $pagination['start']; $i <= $pagination['end']; $i++)
				@if($i === $boards->currentPage())
					<li class="active">
				@else
					<li>
				@endif
						<a href="?page={{$i}}">{{$i}}</a>
					</li>
			@endfor
			@if($pagination['next'])
				<li>
					<a href="?page={{$pagination['next']}}">
						<i class="fa fa-angle-right"></i>
					</a>
				</li>
			@endif
		</ul>
		<div class="buttons">
			<a href="/{{$boardid}}/write" class="btn btn-primary" role="button">
				글쓰기
			</a>
		</div>
		<form class="search-form">
			<select id="searchfield" name="search">
				<option {{ (isset($searchfield) && $searchfield === "both") ? "selected" : "" }} value="both">제목+내용</option>
				<option {{ (isset($searchfield) && $searchfield === "title") ? "selected" : "" }} value="title">제목</option>
				<option {{ (isset($searchfield) && $searchfield === "content") ? "selected" : "" }} value="content">내용</option>
			</select>
			<input id="searchtext" type="text" class="form-control" name="searchtext" value="{{ empty($searchtext) ? "" : $searchtext }}">
			<button id="btn-search" type="submit" class="btn btn-success">검색</button>
		</form>
	</div>
</div>

@endsection