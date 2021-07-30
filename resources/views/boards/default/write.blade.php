@extends('layouts.layout')

@section('content')

<div class="col-md-8 col-md-offset-2">
	<div class="card-section">
		<div class="card-header">
			<a href="/{{$boardid}}">{{$name}}</a>
			<i class="fa fa-angle-right"></i>
			글쓰기
		</div>
		<div class="card-content">
			<form class="write-form" id="postForm" role="form" method="POST" action="/{{$boardid}}/write" enctype="multipart/form-data" onsubmit="return postForm('main')">
				{{ csrf_field() }}
				<div class="form-group">
					<input id="title" type="text" name="title" value="{{ old('title') }}" placeholder="제목" class="input-title" required autofocus>
					 @if ($errors->has('title'))
					<div class="help-block">
						<strong>{{ $errors->first('title') }}</strong>
					</div>
					@endif
				</div>
				
				<div class="main summernote-wrapper">
					<textarea class="summernote" name="content"></textarea>
				</div>
				 
				<div class="dropzone" id="dropzone"></div>

				<div class="options">
					<div class="ananymous">
						<label>
							<input type="checkbox" name="anonymous" value="1">
							익명
						</label>
					</div>
					<div class="buttons">
						<a href="/{{$boardid}}" class="btn btn-danger">
							취소
						</a>
						<button type="submit" class="btn btn-primary">
							글쓰기
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