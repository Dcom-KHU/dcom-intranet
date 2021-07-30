@extends('layouts.layout')

@section('content')

<?php
$split = explode("|", $board->title);
$subject = $split[0];
$professor = $split[1];
?>

<script>
$(function() {
	let searchPos = 0;
	$("#input-subject").on("keyup", function(evt) {
		if(evt.originalEvent.key === "Process" && ["ArrowUp","ArrowDown","Enter"].indexOf(evt.originalEvent.code) >= 0) {
			// do nothing
		} 
		else if(evt.originalEvent.code === "ArrowUp") {
			$("#input-subject-helper li.active").removeClass("active");
			searchPos = Math.max(searchPos - 1, 0);
			$("#input-subject-helper li").eq(searchPos).addClass("active");
		} else if(evt.originalEvent.code === "ArrowDown") {
			$("#input-subject-helper li.active").removeClass("active");
			searchPos = Math.min(searchPos + 1, $("#input-subject-helper li").length - 1);
			$("#input-subject-helper li").eq(searchPos).addClass("active");
		} else if(evt.originalEvent.code === "Enter") {
			$("#input-subject").val($("#input-subject-helper li.active").text());
			$("#input-subject-helper li").remove();
		} else {
			$(this).val($(this).val().replace(/[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"\s]/g, ""));
			$("#input-subject-helper li").remove();
			if($(this).val() != "") {
				$.getJSON("/api/jokbo/subject/" + $(this).val(), function(res) {
					$("#input-subject-helper li").remove();
					if(!!res) {
						res.forEach((v) => {
							$("#input-subject-helper").append(`<li>${v}</li>`);
						});
						$("#input-subject-helper li:first-child").addClass("active");
						searchPos = 0;
					}
					$("#input-subject-helper li").on("mouseover", function(evt) {
						$("#input-subject-helper li.active").removeClass("active");
						$(this).addClass("active");
						searchPos = $(this).index();
					});
					$("#input-subject-helper li").on("mousedown",function(evt) {
						$("#input-subject").val($(this).text());
						$("#input-subject-helper li").remove();
					});
				});
			}
		}
	});
	$("#input-subject").on("blur", function() {
		$("#input-subject-helper li").remove();
	})
	$("#input-subject").on("keydown", function(evt) {
		if(evt.keyCode === 13 && $("#input-subject-helper li").length > 0) {
			evt.preventDefault();
		}
	});
	
	$("#input-professor").on("keyup", function(evt) {
		if(evt.originalEvent.key === "Process" && ["ArrowUp","ArrowDown","Enter"].indexOf(evt.originalEvent.code) >= 0) {
			// do nothing
		} 
		else if(evt.originalEvent.code === "ArrowUp") {
			$("#input-professor-helper li.active").removeClass("active");
			searchPos = Math.max(searchPos - 1, 0);
			$("#input-professor-helper li").eq(searchPos).addClass("active");
		} else if(evt.originalEvent.code === "ArrowDown") {
			$("#input-professor-helper li.active").removeClass("active");
			searchPos = Math.min(searchPos + 1, $("#input-professor-helper li").length - 1);
			$("#input-professor-helper li").eq(searchPos).addClass("active");
		} else if(evt.originalEvent.code === "Enter") {
			$("#input-professor").val($("#input-professor-helper li.active").text());
			$("#input-professor-helper li").remove();
		} else {
			$(this).val($(this).val().replace(/[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"\s]/g, ""));
			$("#input-professor-helper li").remove();
			if($(this).val() != "") {
				$.getJSON("/api/jokbo/professor/" + $(this).val(), function(res) {
					$("#input-professor-helper li").remove();
					if(!!res) {
						res.forEach((v) => {
							$("#input-professor-helper").append(`<li>${v}</li>`);
						});
						$("#input-professor-helper li:first-child").addClass("active");
						searchPos = 0;
					}
					$("#input-professor-helper li").on("mouseover", function(evt) {
						$("#input-professor-helper li.active").removeClass("active");
						$(this).addClass("active");
						searchPos = $(this).index();
					});
					$("#input-professor-helper li").on("mousedown",function(evt) {
						$("#input-professor").val($(this).text());
						$("#input-professor-helper li").remove();
					});
				});
			}
		}
	});
	$("#input-professor").on("blur", function() {
		$("#input-professor-helper li").remove();
	})
	$("#input-professor").on("keydown", function(evt) {
		if(evt.keyCode === 13 && $("#input-professor-helper li").length > 0) {
			evt.preventDefault();
		}
	});

    $("input").tooltip();
});
</script>

<div class="col-md-8 col-md-offset-2">
	<div class="card-section">
		<div class="card-header">
			<a href="/{{$boardid}}">{{$name}}</a>
			<i class="fa fa-angle-right"></i>
			글수정
		</div>
		<div class="card-content">
			<form class="write-form" id="postForm" role="form" method="POST" action="/{{$boardid}}/{{$id}}/modify" enctype="multipart/form-data" onsubmit="return postForm('main')">
				{{ csrf_field() }}
				<div class="form-group">
					<input id="input-subject" type="text" name="subject" value="{{ $subject }}" placeholder="과목" title="띄어쓰기, 특수문자 없이 풀네임으로" class="input-title" autocomplete="off" required autofocus>
					<ul id="input-subject-helper"></ul>
					@if ($errors->has('title'))
					<div class="help-block">
						<strong>{{ $errors->first('title') }}</strong>
					</div>
					@endif
				</div>
				
				<div class="form-group">
					<input id="input-professor" type="text" name="professor" value="{{ $professor }}" title="성이름 형태의 본명으로, 외국인인 경우 PascalCase" placeholder="교수님" class="input-title" autocomplete="off" required>
					<ul id="input-professor-helper"></ul>
				</div>
				
				<div class="main summernote-wrapper">
					<textarea class="summernote" name="content">
						{{ $board->content }}
					</textarea>
				</div>
				 
				<div class="dropzone" id="dropzone"></div>

				<div class="options">
					<div class="ananymous">
						<label>
							<input type="checkbox" name="anonymous" value="1" {!! $board->anonymous==1 ? 'checked' : '' !!}>
							익명
						</label>
					</div>
					<div class="buttons">
						<a href="/{{$boardid}}/{{$id}}" class="btn btn-danger">
							취소
						</a>
						<button type="submit" class="btn btn-primary">
							수정
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	Dropzone.autoDiscover = false;
	var myDropzone = new Dropzone("div#dropzone", {
	   url: '/uploadfile',
	   params: {
		  _token: "{{ Session::getToken() }}",
	   },
	   success: function(file, response) {
		  var link = '<p><a href="/download/'+response+'">'+file.name+'</a></p>';
		  $('.main textarea[name="content"]').summernote('pasteHTML', link);
	   }
	});
</script>
@endsection