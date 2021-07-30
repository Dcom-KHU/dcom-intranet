@extends('layouts.layout')

@section('content')
<style>
#search-group {
	position:relative;
}
#search-group #search-helper {
	position:absolute;
	left:0;
	width:100%;
	padding:5px 5px 0 5px;
	box-sizing:border-box;
}
#search-group #search-helper li {
	padding:8px 10px;
	background-color:#fff;
	border:1px solid #ddd;
	margin-top:-1px;
	font-size:0.9em;
	cursor:pointer;
}
#search-group #search-helper li.active {
	background-color:#eee;
}
</style>
<script>
$(function() {
	$(".search-form").on("submit", () => {
		search("{{$boardid}}");
		return false;
	});
	let searchPos = 0;
	$("#searchtext").on("keyup", function(evt) {
		if(evt.originalEvent.key === "Process" && ["ArrowUp","ArrowDown","Enter"].indexOf(evt.originalEvent.code) >= 0) {
			// do nothing
		} 
		else if(evt.originalEvent.code === "ArrowUp") {
			$("#search-helper li.active").removeClass("active");
			searchPos = Math.max(searchPos - 1, 0);
			$("#search-helper li").eq(searchPos).addClass("active");
		} else if(evt.originalEvent.code === "ArrowDown") {
			$("#search-helper li.active").removeClass("active");
			searchPos = Math.min(searchPos + 1, $("#search-helper li").length - 1);
			$("#search-helper li").eq(searchPos).addClass("active");
		} else if(evt.originalEvent.code === "Enter") {
			$("#searchtext").val($("#search-helper li.active").text());
			$("#search-helper li").remove();
		} else {
			$(this).val($(this).val().replace(/\s/g, ""));
			$("#search-helper li").remove();
			if($(this).val() != "") {
				$.getJSON("/api/jokbo/" + $("#searchfield").val() + "/" + $(this).val(), function(res) {
					$("#search-helper li").remove();
					if(!!res) {
						res.forEach((v) => {
							$("#search-helper").append(`<li>${v}</li>`);
						});
						$("#search-helper li:first-child").addClass("active");
						searchPos = 0;
					}
					$("#search-helper li").on("mouseover", function(evt) {
						$("#search-helper li.active").removeClass("active");
						$(this).addClass("active");
						searchPos = $(this).index();
					});
					$("#search-helper li").on("mousedown", function(evt) {
						$("#searchtext").val($(this).text());
						$("#search-helper li").remove();
					});
				});
			}
		}
	});
	$("#searchtext").on("blur", function() {
		$("#search-helper li").remove();
	});
	$("#searchtext").on("keydown", function(evt) {
		if(evt.keyCode === 13 && $("#search-helper li").length > 0) {
			evt.preventDefault();
		}
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
				<?php
				$splited = explode("|", $board->title);
				$subject = $splited[0];
				$professor = $splited[1];
				?>
				<li>
					<div class="list-long">
						<a href="/{{$boardid}}/{{$board->id}}">
							{{$subject}}
							<?php $commentcount = App\Comment::where('boardid', $board->id)->get()->count()?>
							[{{$commentcount}}]
						</a>
					</div>
					<div class="list-breakline">
						<div class="list-author list-professor">
							{{$professor}} 교수님
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
				올리기
			</a>
		</div>
		<form class="search-form">
			<select id="searchfield" name="search">
				<option {{ (isset($searchfield) && $searchfield === "title") ? "selected" : "" }} value="title">과목+교수님</option>
				<option {{ (isset($searchfield) && $searchfield === "subject") ? "selected" : "" }} value="subject">과목</option>
				<option {{ (isset($searchfield) && $searchfield === "professor") ? "selected" : "" }} value="professor">교수님</option>
			</select>
			<div id="search-group">
				<input id="searchtext" type="text" class="form-control" name="searchtext" value="{{ empty($searchtext) ? "" : $searchtext }}" autocomplete="off">
				<ul id="search-helper"></ul>
			</div>
			<button id="btn-search" type="submit" class="btn btn-success">검색</button>
		</form>
	</div>
</div>

@endsection