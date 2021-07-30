@extends('layouts.app')

@section('content')
<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">글수정</div>
		<div class="panel-body">
		
				<?php $board=App\Board::where('id', $id)->first()?>
		
				<form class="form-horizontal" id="postForm" role="form" method="POST" action="/{{$boardid}}/{{$id}}/modify" enctype="multipart/form-data" onsubmit="return postForm('main')">
				{{ csrf_field() }}
				
				<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
					<div class="col-md-12">
						<label for="title">
							글제목
						</label>
						<input id="title" type="title" class="form-control" name="title" value="{{ $board->title }}" required autofocus>
						 @if ($errors->has('title'))
							<span class="help-block">
								<strong>{{ $errors->first('title') }}</strong>
							</span>
						@endif
					</div>
				</div>
				

				 <input type="hidden" name="anonymous" value="0">
				 <fieldset>
					<div class="main" id="standard">
					   <textarea id="summernote" name="content">
							{{ $board->content }}
					   </textarea>
					</div>
				 </fieldset>
				 
				 <div class="dropzone" id="dropzone"></div>
				 
				 <script>
					// main Dropzone
					Dropzone.autoDiscover = false;
					var myDropzone = new Dropzone("div#dropzone", {
					   url: '/uploadfile',
					   params: {
						  _token: "{{ Session::getToken() }}",
					   },
					   success: function(file, response) {
						  var link = '<a href="http://dcomstudy.com/download/'+response+'">'+file.name+'</a></br>';
						  $('.main textarea[name="content"]').summernote('pasteHTML', link);
					   }
					});
				 </script>
				 


				 <div class="col-md-12">
					 <span id="toggle">
						<input type="checkbox" name="toggle" id="anonymous" data-onstyle="success" data-offstyle="danger" {!! $board->anonymous==0 ? 'checked' : '' !!}>
						<input type="hidden" name="anonymous" value="0">
						
						<script>
							$(function() {
								$('#anonymous').bootstrapToggle({
									on: '실명',
									off: '익명'
								})
								

								$( "#anonymous" ).change(function() {
									if($('#anonymous').is(":checked")) {
										$('input[name=anonymous]').val('0');
									}else{
										$('input[name=anonymous]').val('1');
									}
								});
							})
						</script>
					</span>
				
					<button type="submit" class="btn btn-success" style="float:right;">
					   글수정
					</button>
				 </div>


			</form>

		</div>
	</div>
</div>
@endsection